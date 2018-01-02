<?php
/*
** cbe_frontauth
** Client-side textpattern plugin
** Connect (and disconnect) from frontend to backend
** Establishes bidirectional links between article display and edition
** Claire Brione - http://www.clairebrione.com/
**
** 0.1-dev - 22 Jul 2011 - Restricted development release
** 0.2-dev - 23 Jul 2011 - Restricted development release
** 0.3-dev - 24 Jul 2011 - Restricted development release
** 0.4-beta- 26 Jul 2011 - Restricted beta release
** 0.5-beta- 27 Jul 2011 - First public beta release
** 0.6-beta- 29 Jul 2011 - Optimizations to avoid multiple calls to database
**                           when retrieving user's informations
**                         Added name and privilege controls
**                           à la <txp:rvm_if_privileged /> (http://vanmelick.com/txp/)
**                         Minor changes to documentation
** 0.7-beta- 06 Aug 2011 - Introduces <txp:cbe_frontauth_edit_article />
**                         CSRF protection ready
**                         Documentation improvements
** 0.7.1   - 05 Jan 2012 - Documentation addenda
** 0.8     - 10 Jan 2012 - Introduces <txp:cbe_frontauth_loginwith />
**                             http://forum.textpattern.com/viewtopic.php?pid=256632#p256632
**                         txp:cbe_frontauth_loginwith (*auto*|username|email)
** 0.9     - 21 Mar 2012 - Added callback hooks (reset and change password)
** 0.9.1   - 22 Mar 2012 - Fixed missing attributes (show_login and show_change) for cbe_frontauth_box
** 0.9.2   - ?? ??? 2012 - ??
** 0.9.3   - 22 Aug 2012 - Doc typo for cbe_frontauth_invite
** 0.9.4   - 27 Mar 2013 - Missing initialization for cbe_frontauth_whois
**                         Error message when login fails
**                         Local language strings
** 0.9.5   - 04 Apr 2014 - Missing last access storage
** 0.9.6   - 07 Apr 2014 - Error when passing presentational attributes from cbe_frontauth_edit_article to cbe_frontauth_link
**
** TODO
** - break, breakclass -> in progress, full tests needed
** - enhence error messages ?
**
 ************************************************************************/

/**************************************************
 **
 ** Local language strings, possible customisation here
 **
 **************************************************/
function _cbe_fa_lang()
{
    return( array( 'login_failed'         => "votre identifiant et/ou votre mot de passe sont incorrects"
    , 'login_to_textpattern' => gTxt( 'login_to_textpattern' )
    , 'name'                 => gTxt( 'name' )
    , 'password'             => gTxt( 'password' )
    , 'log_in_button'        => gTxt( 'log_in_button' )
    , 'stay_logged_in'       => gTxt( 'stay_logged_in' )
    , 'logout'               => gTxt( 'logout' )
    , 'edit'                 => gTxt( 'edit' )
    , 'change_password'      => gTxt( 'change_password' )
    , 'password_reset'       => gTxt( 'password_reset' )
    )
    ) ;
}
/**************************************************
 **
 ** Don't edit further
 **
 **************************************************/

/**************************************************
 **
 ** Available tags
 **
 **************************************************/

/* == Shortcuts for cbe_frontauth() == */

// -- Global init for redirection after login and/or logout
// -------------------------------------------------------------------
function cbe_frontauth_redirect( $atts )
{
    return( _cbe_fa_init( $atts, 'redir' ) ) ;
}

// -- Global init for login/logout invites
// -------------------------------------------------------------------
function cbe_frontauth_invite( $atts )
{
    return( _cbe_fa_init( $atts, 'invite' ) ) ;
}

// -- Global init for login/logout buttons/link labels
// -------------------------------------------------------------------
function cbe_frontauth_label( $atts )
{
    return( _cbe_fa_init( $atts, 'label' ) ) ;
}

// -- Global init for login with user name, email, or automatic detection
// -------------------------------------------------------------------
function cbe_frontauth_loginwith( $atts )
{
    return( _cbe_fa_init( $atts, 'with' ) ) ;
}

// -- Login / Logout box
// -------------------------------------------------------------------
function cbe_frontauth_box( $atts, $thing = '' )
{
    $public_atts = lAtts( array( 'login_invite'  => _cbe_fa_gTxt( 'login_to_textpattern' )
        , 'logout_invite' => ''
        , 'show_change'   => '1'
        , 'show_reset'    => '1'
        , 'tag_invite'    => ''
        , 'login_label'   => _cbe_fa_gTxt( 'log_in_button' )
        , 'logout_label'  => _cbe_fa_gTxt( 'logout' )
        , 'logout_type'   => 'button'
        , 'tag_error'     => 'span'
        , 'class_error'   => 'cbe_fa_error'
        )
        + _cbe_fa_format()
        , $atts ) ;

    return( cbe_frontauth( $public_atts
        , $thing ? $thing : '<p><txp:text item="logged_in_as" /> <txp:cbe_frontauth_whois wraptag="span" class="user"/></p>'
    ) ) ;

}

// -- Standalone login form
// -------------------------------------------------------------------
function cbe_frontauth_login( $atts, $thing = '' )
{
    return( _cbe_fa_inout_process( 'login', $atts, $thing ) ) ;
}

// -- Standalone logout form / link
// -------------------------------------------------------------------
function cbe_frontauth_logout( $atts, $thing = '' )
{
    return( _cbe_fa_inout_process( 'logout', $atts, $thing ) ) ;
}

// -- Protect parts from non-connected viewers
// -------------------------------------------------------------------
function cbe_frontauth_protect( $atts, $thing )
{
    $public_atts = lAtts( array( 'link'      => ''
        , 'linklabel' => ''
        , 'target'    => '_self'
        , 'name'      => ''
        , 'level'     => ''
        )
        + _cbe_fa_format()
        , $atts ) ;

    if( $public_atts['target'] == '_get' )
        $public_atts['target'] = '_self' ;

    return( cbe_frontauth( array( 'login_invite' => '' , 'logout_invite' => ''
        , 'show_login'   => '0', 'show_logout'   => '0'
        , 'show_reset'   => '0', 'show_change'   => '0' ) + $public_atts
        , $thing
    ) ) ;
}
function cbe_frontauth_if_logged( $atts, $thing )
{
    return( cbe_frontauth_protect( $atts, $thing ) ) ;
}
function cbe_frontauth_if_connected( $atts, $thing )
{
    return( cbe_frontauth_protect( $atts, $thing ) ) ;
}

/* == Elements == */

// -- Generates input field for name
// -------------------------------------------------------------------
function cbe_frontauth_logname( $atts, $defvalue=null )
{
    return( _cbe_fa_identity( 'name', $atts, $defvalue ) ) ;
}

// -- Generates input field for password
// -------------------------------------------------------------------
function cbe_frontauth_password( $atts, $defvalue=null )
{
    return( _cbe_fa_identity( 'password', $atts, $defvalue ) ) ;
}

// -- Generates checkbox for stay (connected on this browser)
// -------------------------------------------------------------------
function cbe_frontauth_stay( $atts )
{
    extract( lAtts( array ( 'label' => _cbe_fa_gTxt( 'stay_logged_in' )
            )
            + _cbe_fa_format()
            , $atts
        )
    ) ;

    $out  = checkbox('p_stay', 1, cs('txp_login'), '', 'stay') ;
    $out .= '<label for="stay">'.$label.'</label>' ;
    return( doTag( $out, $wraptag, $class ) ) ;
}

// -- Generates submit button
// -------------------------------------------------------------------
function cbe_frontauth_submit( $atts )
{
    $public_atts = lAtts( array ( 'label' => ''
        , 'type'  => 'login'
        )
        + _cbe_fa_format()
        , $atts
    ) ;

    return( _cbe_fa_button( $public_atts ) ) ;
}

// -- Displays connected user's informations
// -------------------------------------------------------------------
function cbe_frontauth_whois( $atts )
{
    extract( lAtts( array ( 'type'       => 'name'
            , 'format'     => ''
            )
            + _cbe_fa_format()
            , $atts
        )
    ) ;

    $types = do_list( $type ) ;
    $whois = cbe_frontauth( array( 'init' => '0', 'value' => $types ) ) ;

    if( isset( $whois['last_access'] ) )
    {
        global $dateformat ;
        $whois['last_access'] = safe_strftime( $format ? $format : $dateformat, strtotime( $whois['last_access'] ) ) ;
    }

    return( doWrap( $whois, $wraptag, $break, $class, $breakclass ) ) ;
}

/* == Off-topic, but useful == */

// -- Generates a link, normal or with a GET parameter
// -------------------------------------------------------------------
function cbe_frontauth_link( $atts )
{   // $class applies to anchor if no $wraptag supplied
    extract( lAtts( array ( 'label'  => ''
            , 'link'   => ''
            , 'target' => '_self'
            )
            + _cbe_fa_format()
            , $atts
        )
    ) ;

    $link = doStripTags( $link ) ;
    $out = _cbe_fa_link( compact( 'link', 'target' ) ) ;
    $out = href( $label, $out
        , (($target !== '_get')  ? ' target="'.$target.'"' : '')
        . ((!$wraptag && $class) ? ' class="'.$class.'"'   : '') ) ;
    return( doTag( $out, $wraptag, $class ) ) ;
}

// -- Returns path to textpattern backend
// -------------------------------------------------------------------
function cbe_frontauth_backend()
{
//            . substr(strrchr(txpath, "/"), 1)
    return( preg_replace('|//$|','/', rhu.'/')
        . substr(strrchr(txpath, DS), 1)
        . '/index.php'
    ) ;
}

// -- Returns button (standalone) or link to edit current article
// -------------------------------------------------------------------
function cbe_frontauth_edit_article( $atts )
{
    global $thisarticle ;
    assert_article() ;

    extract( lAtts( array ( 'label' => _cbe_fa_gTxt( 'edit' )
            , 'type'  => 'button'
            )
            + _cbe_fa_format()
            , $atts
        )
    ) ;

    $path_parts = array( 'event'      => 'article'
    , 'step'       => 'edit'
    , 'ID'         => $thisarticle['thisid']
    ) ;

    if( $type == 'button' )
    {
        $out = array() ;

        foreach( $path_parts as $part => $value )
            $out[] = hInput( $part, $value ) ;

        $out[] = cbe_frontauth_submit( array( 'label' => $label, 'type' => '', 'class' =>'publish' ) ) ;

        return( _cbe_fa_form( array( 'statements' => join( n, $out ) ) ) ) ;
    }
    elseif( $type == 'link' )
    {
        $path_parts[ '_txp_token' ] = form_token() ;
        array_walk( $path_parts, create_function( '&$v, $k', '$v = $k."=".$v ;' ) ) ;
        $link = cbe_frontauth_backend() . '?' . join( '&', $path_parts ) ;

        return( cbe_frontauth_link( compact( 'link', 'label'
                , array_keys( _cbe_fa_format() )
            )
        )
        ) ;
    }
    else
        return ;
}

/**************************************************
 **
 ** Utilities (kinda private functions)
 **
 **************************************************/

// -- Gets and returns local lang strings (txp admin + plugin specifics)
// -------------------------------------------------------------------
function _cbe_fa_gTxt( $text, $atts = array() )
{
    static $aTexts = array() ;
    if( ! $aTexts )
        $aTexts = _cbe_fa_lang() ;

    return( isset( $aTexts[ $text ] ) ? strtr( $aTexts[ $text ], $atts ) : gTxt( $text ) ) ;
}

// -- Common presentational attributes
// -------------------------------------------------------------------
function _cbe_fa_format()
{
    return( array( 'wraptag'    => ''
    , 'class'      => ''
    , 'break'      => ''
    , 'breakclass' => ''
    )
    ) ;
}

// -- Global initialisations (redirect, invite, label, loginwith)
// -------------------------------------------------------------------
function _cbe_fa_init( $atts, $type )
{
    extract( lAtts( array ( 'for' => '', 'value' => '' ), $atts ) ) ;
    if( $for === '' )
        $for = 'login' ;
    $init_for = do_list( $for ) ;

    if( ($index=array_search( 'logged', $init_for )) !== false )
        $init_for[ $index ] = 'logout' ;

    array_walk( $init_for, create_function( '&$v, $k, $p', '$v = $v."_".$p ;'), $type ) ;

    if( ($init_list = @array_combine( $init_for, do_list( $value ) )) === false )
        return ;

    cbe_frontauth( array( 'init' => '1' ) + $init_list ) ;
    return ;
}

// -- Retrieve user's info, if connected
// -- textpattern/lib/txp_misc.php - is_logged_in() as a starting point
// -------------------------------------------------------------------
function _cbe_fa_logged_in( &$user, $txp_user = null )
{
    if( $txp_user !== null )
        $name = $txp_user ;
    elseif( !($name = substr(cs('txp_login_public'), 10)) )
    {
        $user[ 'name' ] = false ;
        return( false ) ;
    }

    $rs = safe_row('nonce, name, RealName, email, privs, last_access', 'txp_users', "name = '".doSlash($name)."'");

    if( $rs && ($txp_user !== null || substr(md5($rs['nonce']), -10) === substr(cs('txp_login_public'), 0, 10) ) )
    {
        unset( $rs[ 'nonce' ] ) ;
        $user = $rs ;
        return( true ) ;
    }
    else
    {
        $user[ 'name' ] = false ;
        return( false ) ;
    }
}

// -- Checks current user against required privileges
// -- Thanks to Ruud Van Melick's rvm_privileged (http://vanmelick.com/txp/)
// -------------------------------------------------------------------
function _cbe_fa_privileged( $r_name, $r_level, $u_name, $u_level )
{
    $chk_name  = !$r_name  || in_array( $u_name , do_list( $r_name  ) ) ;
    $chk_level = !$r_level || in_array( $u_level, do_list( $r_level ) ) ;
    return( $chk_name && $chk_level ) ;
}

// -- Generates input field for name or password
// -------------------------------------------------------------------
function _cbe_fa_identity( $field, $atts, $value=null )
{
    extract( lAtts( array ( 'label'     => _cbe_fa_gTxt( $field )
            , 'label_sfx' => ''
            )
            + _cbe_fa_format()
            , $atts
        )
    ) ;

    $$field = '' ;
    if( $field == 'name' && cs('cbe_frontauth_login') != '' )
        list($name) = explode( ',', cs('cbe_frontauth_login') ) ;

    $out = array() ;
    $out[] = '<label for="'.$field.$label_sfx.'">'.$label.'</label>' ;
    $out[] =  fInput( ($field == 'name')    ? 'text'     : 'password'
        ,(($field == 'name')    ? 'p_userid' : 'p_password') . $label_sfx
        , ($field == 'name')    ? $name      : ($value !== null ? $value : '')
        , (!$wraptag && $class) ? $class     : ''
        , '', '', '', '', $field.$label_sfx ) ;

    return( doWrap( $out, $wraptag, $break, $class, $breakclass ) ) ;
}

// -- Prepare call to cbe_frontauth() for login/logout form/link
// -------------------------------------------------------------------
function _cbe_fa_inout_process( $inout, $atts, $thing = '' )
{
    $plus_atts = ($inout == 'logout' )
        ? array( 'type'        => 'button'
        , 'show_change' => '1'      )
        : array( 'show_stay'   => '0'
        , 'show_reset'  => '1'      ) ;
    $public_atts = lAtts( array ( 'invite'     => _cbe_fa_gTxt( ($inout == 'login') ? 'login_to_textpattern'
            : '' )
        , 'tag_invite' => ''
        , 'label'      => _cbe_fa_gTxt( ($inout == 'login') ? 'log_in_button'
                : 'logout' )
        , 'form'       => ''
        , 'tag_error'  => 'span', 'class_error' => 'cbe_fa_error'
        )
        + $plus_atts + _cbe_fa_format()
        , $atts ) ;

    if( isset( $public_atts['invite'] ) )
    {
        $public_atts[$inout.'_invite'] = $public_atts['invite'] ;
        unset( $public_atts['invite'] ) ;
    }
    if( isset( $public_atts['label'] ) )
    {
        $public_atts[$inout.'_label'] = $public_atts['label'] ;
        unset( $public_atts['label'] ) ;
    }
    if( isset( $public_atts['form'] ) )
    {
        $public_atts[$inout.'_form'] = $public_atts['form'] ;
        unset( $public_atts['form'] ) ;
    }
    if( isset( $public_atts['type'] ) )
    {
        $public_atts[$inout.'_type'] = $public_atts['type'] ;
        unset( $public_atts['type'] ) ;
    }
    if( $thing )
        $public_atts[$inout.'_form'] = $thing ;

    $show = ($inout == 'login') ? 'logout' : 'login' ;
    return( cbe_frontauth( array( 'show_'.$show => '0' ) + $public_atts ) ) ;
}

// -- Encloses statements in a submit form
// -------------------------------------------------------------------
function _cbe_fa_form( $atts )
{
    extract( lAtts( array( 'statements' => ''
            , 'action'     => cbe_frontauth_backend()
            , 'method'     => 'post'
            )
            , $atts
        )
    ) ;

    if( ! $statements )
        return ;

    return( '<form action="'.$action.'" method="'.$method.'">'
        .n. $statements
        .n. '</form>' ) ;
}

// -- Generates a button (primary purpose : login/logout button)
// -- Extended to 'edit' (just in case) - 0.7
// -- Note: providing a label and setting type to blank works too
// -------------------------------------------------------------------
function _cbe_fa_button( $atts )
{
    extract( $atts ) ; // 'label', 'type', 'wraptag', class'

    if( ! $label and ! ($label = cbe_frontauth( array( 'init' => '0', 'value' => $type.'_label' ) )) )
        $label = _cbe_fa_gTxt( ($type == 'logout' || $type == 'edit' ) ? $type : 'log_in_button' ) ;

    $out = fInput( 'submit', '', $label, (!$wraptag && $class) ? $class : '' ) ;

    if( $type == 'logout' )
        $out .= hInput( 'p_logout', '1' ) ;
    elseif( $type == 'edit' )
        $out .= tInput() ;

    return( doTag( $out, $wraptag, $class ) ) ;
}

// -- Generates a link (primary purpose : logout link)
// -------------------------------------------------------------------
function _cbe_fa_link( $atts )
{
    extract( $atts ) ; // 'link', 'target'

    if( $target == '_get' )
    {
        $uri = serverSet( 'REQUEST_URI'  ) ;
        $qus = serverSet( 'QUERY_STRING' ) ;

        $len_uri = strlen( $uri ) ;
        $len_qus = strlen( $qus ) ;

        $uri = ($len_qus > 0) ? substr( $uri, 0, $len_uri-$len_qus-1 ) : $uri ;
        $qus = $qus . ($len_qus > 0 ? '&' : '') . $link ;

        $out = (substr( $uri, -1 ) !== '?' ) ? ($uri.'?'.$qus) : ($uri.$qus) ;
    }
    else
    {
        $out = $link ;
    }

    return( $out ) ;
}

// -- Generates login/logout form or logout link
// -------------------------------------------------------------------
function _cbe_fa_inout( $atts )
{
    extract( $atts ) ;

    $out = array() ;

    if( $form )
        $out[] = ($f=@fetch_form( $form )) ? parse( $f ) : parse( $form ) ; // label takes precedence here
    else
    {
        if( isset( $show_stay ) )
        {   // login

            $out[] = cbe_frontauth_logname(  array( 'class' => 'edit')
                +  compact( 'break', 'breakclass' ) ) ;
            $out[] = cbe_frontauth_password( array( 'class' => 'edit')
                + compact( 'break', 'breakclass' ) ) ;
            if( $show_stay )
                $out[] = cbe_frontauth_stay( array() ) ;

            $out[] = cbe_frontauth_submit( array( 'label' => $label, 'class' => 'publish' ) ) ;

        }
        else
        {   // logout
            $out[] = ($type == 'button')
                ? cbe_frontauth_submit( array( 'label' => $label, 'type' => 'logout'
                , 'class' => $class ? $class : 'publish' ) )
                : cbe_frontauth_link( array( 'label' => $label, 'link' => 'logout=1', 'target' => '_get'
                , 'class' => $class ? $class : 'publish' ) ) ;
        }
    }

//    $out = join( n, $out ) ;
    $out = doWrap( $out, $wraptag, $break, '', $breakclass ) ;
    return( (isset( $type ) && $type=='link')
        ? $out
        : _cbe_fa_form( array( 'statements' => $out, 'action' => page_url( array() ) ) ) ) ;
}

/* == Backbone == */

// -- Cookie mechanism - from textpattern/include/txp_auth.php - doTxpValidate()
// -------------------------------------------------------------------
function _cbe_fa_auth( $redir, $p_logout, $p_userid='', $p_password='', $p_stay='' )
{
    defined('LOGIN_COOKIE_HTTP_ONLY') || define('LOGIN_COOKIE_HTTP_ONLY', true);
    $hash  = md5(uniqid(mt_rand(), TRUE));
    $nonce = md5($p_userid.pack('H*',$hash));
    $pub_path = preg_replace('|//$|','/', rhu.'/') ;
    $adm_path = $pub_path . substr(strrchr(txpath, DS), 1) . '/' ;

    if( $p_logout )
    {
        $log_name = false ;

        safe_update( 'txp_users'
            , "nonce = '".doSlash($hash)."'"
            , "name = '".doSlash($p_userid)."'"
        ) ;

        setcookie( 'txp_login'
            , ''
            , time()-3600
            , $adm_path
        ) ;

        setcookie( 'txp_login_public'
            , ''
            , time()-3600
            , $pub_path
        ) ;

        setcookie( 'cbe_frontauth_login'
            , ''
            , time()-3600
            , $pub_path
        ) ;
    }
//    elseif( ($log_name = txp_validate( $p_userid, $p_password, false )) !== false )
    elseif( ($log_name = txp_validate( $p_userid, $p_password )) !== false )
    {
        safe_update( 'txp_users'
            , "nonce = '".doSlash($nonce)."'"
            , "name = '".doSlash($p_userid)."'"
        ) ;

        setcookie( 'txp_login'
            , $p_userid.','.$hash
            , ($p_stay ? time()+3600*24*365 : 0)
            , $adm_path
            , null
            , null
            , LOGIN_COOKIE_HTTP_ONLY
        ) ;

        setcookie( 'txp_login_public'
            , substr(md5($nonce), -10).$p_userid
            , ($p_stay ? time()+3600*24*30 : 0)
            , $pub_path
        ) ;

        if( $p_stay )
            setcookie( 'cbe_frontauth_login'
                , $p_userid.','.$hash
                , time()+3600*24*365
                , $pub_path
            ) ;
    }

    if( $redir && ( $p_logout || $log_name !== false ) )
    {
        header( "Location:$redir" ) ;
        exit ;
    }

    return( $log_name ) ;
}

// -- Get the job done
// -------------------------------------------------------------------
function cbe_frontauth( $atts, $thing = null )
{
    include_once( txpath.'/include/txp_auth.php' ) ;
    global $txp_user ;
    static $inits = array( 'login_invite' => '' , 'logout_invite' => '' , 'tag_invite' => ''
    , 'login_label'  => '' , 'logout_label'  => ''
    , 'login_redir'  => '' , 'logout_redir'  => ''
    , 'login_with'   => ''
    ) ;
    static $cbe_fa_user = array( 'name'  => false , 'RealName'    => '' , 'email' => ''
    , 'privs' => ''    , 'last_access' => ''
    ) ;

    if( isset( $atts['init'] ) )
    {
        if( $atts['init'] )
        {
            unset( $atts['init'] ) ;

            foreach( $atts as $param => $value )
                $inits[$param] = $value ;

            return ;
        }
        else
        {
            if( is_array( $atts[ 'value' ] ) )
            {
                $whois = array() ;
                if( ! $cbe_fa_user[ 'name' ] ) _cbe_fa_logged_in( $cbe_fa_user ) ;
                foreach( $atts[ 'value' ] as $type )
                    $whois[ $type ] = $cbe_fa_user[ $type ] ;

                return( $whois ) ;
            }
            else
                return( isset( $inits[ $atts[ 'value' ] ] ) ? $inits[ $atts[ 'value' ] ] : '' ) ;
        }
    }

    $def_atts = array( 'form'          => ''
    , 'tag_invite'    => ''
    , 'show_login'    => '1'
    , 'login_invite'  => _cbe_fa_gTxt( 'login_to_textpattern' )
    , 'login_form'    => ''
    , 'login_label'   => _cbe_fa_gTxt( 'log_in_button' )
    , 'login_with'    => 'auto'
    , 'login_redir'   => ''
    , 'show_logout'   => '1'
    , 'logout_invite' => ''
    , 'logout_form'   => ''
    , 'logout_label'  => _cbe_fa_gTxt( 'logout' )
    , 'logout_type'   => 'button'
    , 'logout_redir'  => ''
    , 'show_stay'     => '0'
    , 'show_reset'    => '1'
    , 'show_change'   => '1'
    , 'link'          => ''
    , 'linklabel'     => ''
    , 'target'        => '_self'
    , 'name'          => ''
    , 'level'         => ''
    , 'tag_error'     => ''
    , 'class_error'   => ''
    ) ;

    $ini_atts = array() ;
    foreach( $inits as $param => $value )
    {   /* Inits take precedence on default values */
        if( !isset( $atts[$param] ) || $atts[$param] === $def_atts[$param] )
            $ini_atts[$param] = $value ;
    }

    extract( lAtts( $def_atts + _cbe_fa_format(), array_merge( $atts, array_filter( $ini_atts ) ) ) ) ;

    extract( psa( array( 'p_userid', 'p_password', 'p_stay', 'p_reset', 'p_logout', 'p_change' ) ) ) ;
    $logout = gps( 'logout' ) ;
    $p_logout = $p_logout || $logout ;
    $reset = gps( 'reset' ) ;
    $p_reset = $p_reset || $reset ;
    $change = gps( 'change' ) ;
    $p_change = $p_change || $change ;

    if( $p_userid && $p_password )
    {
        $username = ($login_with == 'auto') ? safe_count( 'txp_users', "name='$p_userid'" ) : 0 ;

        if( $username == 0 && $login_with != 'username' )
        { // Email probably given, retrieve user name if possible
            $p_userid = safe_rows( 'name', 'txp_users', "email='$p_userid'" ) ;
            $p_userid = (count( $p_userid ) == 1) ? $p_userid[ 0 ][ 'name' ] : '' ;
        }

        $login_redir = ($login_redir==='link') ? $link : $login_redir ;
        $login_failed = ($txp_user = _cbe_fa_auth( $login_redir, 0, $p_userid, $p_password, $p_stay )) === false ;
        _cbe_fa_logged_in( $cbe_fa_user, $txp_user ) ;
    }
    elseif( $p_logout )
    {
        if( $logout && !$logout_redir )
            $logout_redir = preg_replace( "/[?&]logout=1/", "", serverSet('REQUEST_URI') ) ;

        $txp_user = _cbe_fa_auth( $logout_redir, 1 ) ;
        _cbe_fa_logged_in( $cbe_fa_user, false ) ;
    }
    else
        $txp_user = _cbe_fa_logged_in( $cbe_fa_user ) ? $cbe_fa_user[ 'name' ] : false ;

    $out = array() ;
    $invite = '' ;
    $part_0 = EvalElse( $thing, 0 ) ;
    $part_1 = EvalElse( $thing, 1 ) ;
    if( $txp_user === false )
    {
        $out[] = parse( $part_0 ) ;

        if( $show_login )
        {
            if( $p_reset )
            {   // Resetting password in progress
                $invite = _cbe_fa_gTxt( 'password_reset' ) ;
                $out[]  = callback_event( 'cbefrontauth.reset_password', 'cbe_fa_before_login', 0
                    , ps('step') ? array( 'p_userid'   => $p_userid
                    , 'login_with' => $login_with
                    , 'tag_error'  => $tag_error
                    , 'class_error' => $class_error )
                        : null ) ;
            }
            else
            {   // We are not resetting the password at the moment, display login form
                if( isset( $login_failed ) && $login_failed )
                    $out[] = doTag( _cbe_fa_gTxt( 'login_failed' ), $tag_error, $class_error ) ;
                $invite = $login_invite ;
                $out[]  = _cbe_fa_inout( array( 'label'      => $login_label
                    , 'form'       => $login_form
                    , 'show_stay'  => $show_stay
                    , 'show_reset' => $show_reset
                    ) + compact( 'wraptag', 'class', 'break', 'breakclass' ) ) ;
                if( $show_reset )
                    $out[] = callback_event( 'cbefrontauth.reset_password', 'cbe_fa_after_login' ) ;
            }
        }
    }
    else
    {
        if( (!$name && !$level)
            ||
            _cbe_fa_privileged( $name, $level, $cbe_fa_user[ 'name' ], $cbe_fa_user[ 'privs' ] )
        )
        {
            if( $link )
                $out[] = cbe_frontauth_link( array( 'label' => $linklabel ) + compact( 'link', 'target' ) ) ;

            if( $thing )
                $out[] = parse( $part_1 ) ;
            elseif( $form )
                $out[] = parse_form( $form ) ;
        }
        else
            $out[] = parse( $part_0 ) ;

        if( $show_logout )
        {
            if( $p_change )
            {   // Changing password in progress
                $invite = _cbe_fa_gTxt( 'change_password' ) ;
                $out[]  = callback_event( 'cbefrontauth.change_password', 'cbe_fa_before_logout', 0
                    , ps('step') ? array( 'p_userid'     => $txp_user
                    , 'p_password'   => $p_password
                    , 'p_password_1'
                        => strip_tags( ps( 'p_password_1' ) )
                    , 'p_password_2'
                        => strip_tags( ps( 'p_password_2' ) )
                    , 'tag_error'    => $tag_error
                    , 'class_error'  => $class_error )
                        : null ) ;
            }
            else
            {   // We are not changing the password at the moment, display logout form
                $invite = $logout_invite;
                $out[] = _cbe_fa_inout( array( 'label'       => $logout_label
                    , 'form'        => $logout_form
                    , 'type'        => $logout_type
                    , 'show_change' => $show_change
                    , 'p_change'    => $p_change
                    , 'tag_error'   => $tag_error
                    , 'class_error' => $class_error
                    ) + compact( 'wraptag', 'class', 'break', 'breakclass' ) ) ;
                if( $show_change )
                    $out[] = callback_event( 'cbefrontauth.change_password', 'cbe_fa_after_logout' ) ;
            }
        }
    }

//    return( doLabel( $invite, $tag_invite ) . doWrap( $out, $wraptag, $break, $class, $breakclass ) ) ;
    return( doLabel( $invite, $tag_invite ) . doWrap( $out, $wraptag, '', $class ) ) ;
}

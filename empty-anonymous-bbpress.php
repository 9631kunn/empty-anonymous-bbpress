<?php

/**
 * Plugin Name: Empty Anonymous bbPress
 * Description:bbPressで匿名投稿を可能にします。
 * Plugin URI: https://takasaki.work/bbpress/
 */

add_action( 'init', array( 'WPSE_Empty_Anonymous_Replies', 'init' ) );

class WPSE_Empty_Anonymous_Replies
{
        static protected $name  = 'nobody';
        static protected $email = 'nobody@example.com';

        static public function init()
        {
            add_filter( 'bbp_filter_anonymous_post_data',
                         array( __CLASS__, 'bbp_filter_anonymous_post_data' ),
                         11, 2 );
            add_filter( 'bbp_pre_anonymous_post_author_name',
                         array( __CLASS__,  'bbp_pre_anonymous_post_author_name' ) );
            add_filter( 'bbp_pre_anonymous_post_author_email',
                         array( __CLASS__, 'bbp_pre_anonymous_post_author_email' ) );
        }

        static public function bbp_filter_anonymous_post_data( $retval, $r )
        {
            if( self::$name === $r['bbp_anonymous_name']
                && self::$email === $r['bbp_anonymous_email'] )
            {
                // reset the input to skip writing cookies
                $retval = array();

                // trick to activate the IP flood check
                $retval['bbp_anonymous_flood_check'] = '1';
            }
            return $retval;
        }

        static public function bbp_pre_anonymous_post_author_name( $name )
        {
            remove_filter( current_filter(), array( __CLASS__, __FUNCTION__ ) );
            if( empty( $name ) )
                $name = self::$name;

            return $name;
        }

        static public function bbp_pre_anonymous_post_author_email( $email )
        {
            remove_filter( current_filter(), array( __CLASS__, __FUNCTION__ ) );
            if( empty( $email ) )
                $email = self::$email;

            return $email;
        }
    }

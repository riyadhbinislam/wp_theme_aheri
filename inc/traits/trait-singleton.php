<?PHP
/**
 * @package Aheri
 */
namespace AHERI\inc\traits;

trait singleton{

    public function __construct(){

    }
        /*** Prevent object cloning*/
    public function __clone(){

    }

    final public static function get_instance(){

        /*** Collection of instance.** @var array*/

        static $instance = [];
            /**
             * If this trait is implemented in a class which has multiple sub-classes then static::$_instance will be overwritten with the most recent sub-class instance.
             * Thanks to late static binding  we use [ get_called_class() ] to grab the called class name, and store a key=>value pair for each `classname => instance` in self::$_instance for each sub-class.
             */

        $called_class = get_called_class();

        if (!isset( $instance[ $called_class ])){
            $instance[$called_class] = new $called_class();

            do_action( sprintf( 'aheri_singleton_init_%s', $called_class ));
        }

        return $instance[$called_class];

    }
}
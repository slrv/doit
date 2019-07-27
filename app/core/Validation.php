<?php


namespace Core;


class Validation
{
    public static function required( array $required, array $provided ) {
        return array_diff_key( $required, $provided );
    }

    /**
     * Check if field is valid email
     * Return null if valid | return message with error
     *
     * @param string $field
     * @param array $provided
     * @return array|null
     */
    public static function email( string $field, array $provided ): ?string {
        if ( empty( $provided[ $field ] ) ) return 'Not provided';
        if ( filter_var( $provided[ $field ], FILTER_VALIDATE_EMAIL) ) return 'Invalid email';

        return null;
    }

    /**
     * Check if field value length in range
     * Range values can be 0. In this case this value will be skipped
     * Return null if valid | return message with error
     *
     * @param string $field
     * @param array $provided
     * @param int $min
     * @param int $max
     * @return string|null
     */
    public static function minMax( string $field, array $provided, $min = 0, $max = 0 ): ?string {
        if ( empty( $provided[ $field ] ) ) return 'Not provided';
        $length = mb_strlen( $provided[ $field ] );
        if ( $min && $length < $min ) return "String must be not less than $min chars";
        if ( $max && $length > $min ) return "String must be not greater than $max chars";

        return null;
    }
}

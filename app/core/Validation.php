<?php


namespace Core;


class Validation
{
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
        if ( !filter_var( $provided[ $field ], FILTER_VALIDATE_EMAIL) ) return 'Invalid email';

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
        if ( $max && $length > $max ) return "String must be not greater than $max chars";

        return null;
    }

    /**
     * Check if field value in array of valid values
     * Return null if valid | return message with error
     *
     * @param string $field
     * @param array $provided
     * @param array $valid
     * @return string|null
     */
    public static function enum( string $field, array $provided, array $valid ): ?string {
        if ( empty( $provided[ $field ] ) ) return 'Not provided';
        if ( !in_array( $provided[ $field ], $valid ) ) return 'Not available';

        return null;
    }

    /**
     * Check if field value is valid date and in range of dates
     * If $min parameter skipped, using now as min
     * If $max parameter skipped, max validation skipped
     * Return null if valid | return message with error
     *
     * @param string $field
     * @param array $provided
     * @param \DateTime|null $min
     * @param \DateTime|null $max
     * @return string|null
     * @throws \Exception
     */
    public static function dateBetween( string $field, array $provided, \DateTime $min = null, \DateTime $max = null ): ?string {
        if ( empty( $provided[ $field ] ) ) return 'Not provided';
        try {
            $dateTime = new \DateTime( $provided[ $field ] );
        } catch ( \Exception $e ) {
            return 'Not valid date';
        }

        $minTime = $min ? $min->format('Y-m-d H:i:s') : 'now';
        if ( !$min ) $min = new \DateTime();

        if ( $dateTime < $min ) return "Date must be not earlier than $minTime";
        if ( $max && $dateTime > $max ) return "Date must be not later than ".$max->format( 'Y-m-d H:i:s' );

        return null;
    }
}

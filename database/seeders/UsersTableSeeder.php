<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();

        \DB::table('users')->insert(array (
            0 =>
            array (
                'id' => '14',
                'first_name_en' => 'Ima',
                'last_name_en' => 'Rutledge',
                'email' => 'kivibaxoc@mailinator.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$robMUsC/OgooSkZ1TzES1untce4EHfzaIuAkbADRDKFDTs2hHywOO',
                'user_type' => 'user',
                'remember_token' => 'dc651041150b563af507762af74c8f221e46f3db0fff87f1dda866009a1a851f',
                'image' => NULL,
                'original_image' => NULL,
                'telephone' => NULL,
                'liked_school' => '0',
                'created_at' => '2021-03-28 05:58:59',
                'updated_at' => '2021-03-28 05:58:59',
                'first_name_ar' => NULL,
                'email_ar' => NULL,
                'last_name_ar' => NULL,
            ),
            1 =>
            array (
                'id' => '15',
                'first_name_en' => 'Hamish',
                'last_name_en' => 'Solis',
                'email' => 'zerelohuky@mailinator.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$robMUsC/OgooSkZ1TzES1untce4EHfzaIuAkbADRDKFDTs2hHywOO',
                'user_type' => 'user',
                'remember_token' => 'f99f3d96fdaf26664de430e434c546a09c400ff3ffcc2bb09a8885eeaa04b6d5',
                'image' => NULL,
                'original_image' => NULL,
                'telephone' => NULL,
                'liked_school' => '0',
                'created_at' => '2021-03-28 05:59:58',
                'updated_at' => '2021-03-28 05:59:58',
                'first_name_ar' => NULL,
                'email_ar' => NULL,
                'last_name_ar' => NULL,
            ),
            2 =>
            array (
                'id' => '16',
                'first_name_en' => 'Clark',
                'last_name_en' => 'Hughes',
                'email' => 'ziku@mailinator.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$robMUsC/OgooSkZ1TzES1untce4EHfzaIuAkbADRDKFDTs2hHywOO',
                'user_type' => 'user',
                'remember_token' => '9ce53693775dabfc0e8cada404fd0cc8c34f0f949207f2f26e79322c4059cbbd',
                'image' => NULL,
                'original_image' => NULL,
                'telephone' => NULL,
                'liked_school' => '0',
                'created_at' => '2021-03-28 06:00:27',
                'updated_at' => '2021-03-28 06:00:27',
                'first_name_ar' => NULL,
                'email_ar' => NULL,
                'last_name_ar' => NULL,
            ),
            3 =>
            array (
                'id' => '17',
                'first_name_en' => 'Yetta',
                'last_name_en' => 'Fox',
                'email' => 'desezohas@mailinator.com',
                'email_verified_at' => '2021-03-29 05:03:27',
                'password' => '$2y$10$robMUsC/OgooSkZ1TzES1untce4EHfzaIuAkbADRDKFDTs2hHywOO',
                'user_type' => 'user',
                'remember_token' => 'FziVEVCcV9rQz3gERuv6KPLSFXWl79s65GZWXnVuMaZ2SXR9MQcDrytRFfvw',
                'image' => NULL,
                'original_image' => NULL,
                'telephone' => NULL,
                'liked_school' => '0',
                'created_at' => '2021-03-29 05:02:12',
                'updated_at' => '2021-03-29 05:03:27',
                'first_name_ar' => NULL,
                'email_ar' => NULL,
                'last_name_ar' => NULL,
            ),
            4 =>
            array (
                'id' => '18',
                'first_name_en' => 'Michael',
                'last_name_en' => 'Kirkland',
                'email' => 'pydiz@mailinator.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$cJc1RCy3NfcQ/HXqPfG5..4weCCS051COQzPEj1.GdbOc2I650WCm',
                'user_type' => 'user',
                'remember_token' => '1162400dd4841d790a682bb51a0357e31dbbaef7c8fafaab694b42ad3f42fb15',
                'image' => NULL,
                'original_image' => NULL,
                'telephone' => NULL,
                'liked_school' => '0',
                'created_at' => '2021-03-29 08:36:48',
                'updated_at' => '2021-03-29 08:36:48',
                'first_name_ar' => NULL,
                'email_ar' => NULL,
                'last_name_ar' => NULL,
            ),
            5 =>
            array (
                'id' => '19',
                'first_name_en' => 'Michael',
                'last_name_en' => 'Kirkland',
                'email' => 'sspydiz@mailinator.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$s3Nn03nhzIUEN8gcKAcXVe3gcziEqfotcwWGks4kD3mAD1LwxB/NW',
                'user_type' => 'user',
                'remember_token' => 'eyscBBmnDkJ050NUUcJjANy0QSwK9ZgJdvnGiQRr8Eg9F3iNlD7x0NVDOLNx',
                'image' => NULL,
                'original_image' => NULL,
                'telephone' => NULL,
                'liked_school' => '0',
                'created_at' => '2021-03-29 08:37:16',
                'updated_at' => '2021-03-29 08:37:16',
                'first_name_ar' => NULL,
                'email_ar' => NULL,
                'last_name_ar' => NULL,
            ),
            6 =>
            array (
                'id' => '20',
                'first_name_en' => 'Winifred',
                'last_name_en' => 'Todd',
                'email' => 'pexyn@mailinator.com',
                'email_verified_at' => '2021-03-28 06:00:27',
                'password' => '$2y$10$THuP1aKLUXqrWKpbP8Q4qOuGZxXPLDOaslhNktYqrFOHSU1cot7NC',
                'user_type' => 'user',
                'remember_token' => NULL,
                'image' => NULL,
                'original_image' => NULL,
                'telephone' => NULL,
                'liked_school' => '0',
                'created_at' => '2021-03-29 10:15:25',
                'updated_at' => '2021-03-29 10:35:05',
                'first_name_ar' => NULL,
                'email_ar' => NULL,
                'last_name_ar' => NULL,
            ),
            7 =>
            array (
                'id' => '21',
                'first_name_en' => 'Kerry',
                'last_name_en' => 'Ramsey',
                'email' => 'leralecapy@mailinator.com',
                'email_verified_at' => '2021-03-29 10:36:54',
                'password' => '$2y$10$eMNh6i./mQMqtd4W.dQjNuqCsYxoD1qMkwV/YIwZWPAGSI3aoXFga',
                'user_type' => 'user',
                'remember_token' => NULL,
                'image' => NULL,
                'original_image' => NULL,
                'telephone' => NULL,
                'liked_school' => '0',
                'created_at' => '2021-03-29 10:18:51',
                'updated_at' => '2021-03-29 10:36:54',
                'first_name_ar' => NULL,
                'email_ar' => NULL,
                'last_name_ar' => NULL,
            ),
            8 =>
            array (
                'id' => '22',
                'first_name_en' => 'Elton',
                'last_name_en' => 'Branch',
                'email' => 'talokalav@mailinator.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$CLNMIqzL/WeC21JJpB4uNe2sf/cZFyGmntgEDl9onqndexrh3Nnl2',
                'user_type' => 'user',
                'remember_token' => 'be2bb973684b8df60d827c47bb67e74ab8224639af18ae8b8bdfbca18104bf12',
                'image' => NULL,
                'original_image' => NULL,
                'telephone' => NULL,
                'liked_school' => '0',
                'created_at' => '2021-03-29 10:23:43',
                'updated_at' => '2021-03-29 10:23:43',
                'first_name_ar' => NULL,
                'email_ar' => NULL,
                'last_name_ar' => NULL,
            ),
            9 =>
            array (
                'id' => '23',
                'first_name_en' => 'Xenos',
                'last_name_en' => 'England',
                'email' => 'lubo@mailinator.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$/hT1u7mxIZ3FBQ0f6y6a1OmTz9yYdmU4wuAVA5V7xoQum4rNMGJBi',
                'user_type' => NULL,
                'remember_token' => NULL,
                'image' => 'C:\\xampp\\tmp\\phpC6D0.tmp',
                'original_image' => NULL,
                'telephone' => 'Architecto voluptate',
                'liked_school' => '0',
                'created_at' => '2021-03-31 02:29:37',
                'updated_at' => '2021-03-31 02:29:37',
                'first_name_ar' => 'Cedric',
                'email_ar' => NULL,
                'last_name_ar' => 'Hernandez',
            ),
            10 =>
            array (
                'id' => '26',
                'first_name_en' => 'Felicia',
                'last_name_en' => 'Henry',
                'email' => 'vydupikop@mailinator.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$Z6cI0VlAjnJ8ErtULfjEUeAKGF/tSq0hrDUpkbcJv2YapgdzTb/b.',
                'user_type' => 'school_admin',
                'remember_token' => NULL,
                'image' => NULL,
                'original_image' => NULL,
                'telephone' => '51',
                'liked_school' => '0',
                'created_at' => '2021-03-31 03:04:14',
                'updated_at' => '2021-03-31 03:24:06',
                'first_name_ar' => 'Honorato',
                'email_ar' => NULL,
                'last_name_ar' => 'Guerra',
            ),
            11 =>
            array (
                'id' => '27',
                'first_name_en' => 'Fallon',
                'last_name_en' => 'Sosa',
                'email' => 'cegimo@mailinator.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$nh55OoRxkVbpdN8nPSWmAuQmC9L8I9WHbuNRGpZsnpT7lTP4YrdmG',
                'user_type' => 'school_admin',
                'remember_token' => NULL,
                'image' => NULL,
                'original_image' => NULL,
                'telephone' => '94',
                'liked_school' => '0',
                'created_at' => '2021-03-31 03:04:33',
                'updated_at' => '2021-03-31 03:04:33',
                'first_name_ar' => 'Merrill',
                'email_ar' => NULL,
                'last_name_ar' => 'Barrett',
            ),
            12 =>
            array (
                'id' => '28',
                'first_name_en' => 'Quemby',
                'last_name_en' => 'Whitley',
                'email' => 'qege@mailinator.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$Z6cI0VlAjnJ8ErtULfjEUeAKGF/tSq0hrDUpkbcJv2YapgdzTb/b.',
                'user_type' => 'school_admin',
                'remember_token' => NULL,
                'image' => NULL,
                'original_image' => NULL,
                'telephone' => '4',
                'liked_school' => '0',
                'created_at' => '2021-03-31 03:05:11',
                'updated_at' => '2021-03-31 03:05:11',
                'first_name_ar' => 'Timothy',
                'email_ar' => NULL,
                'last_name_ar' => 'Raymond',
            ),
        ));
    }
}

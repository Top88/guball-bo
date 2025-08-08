<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | บรรทัดภาษาต่อไปนี้มีข้อความแสดงข้อผิดพลาดที่ใช้ตามปกติ
    | โดยคลาสตัวตรวจสอบ บางกฎมีหลายเวอร์ชัน เช่น กฎขนาด
    | คุณสามารถปรับแต่งข้อความเหล่านี้ได้ตามความเหมาะสม
    |
    */

    'accepted' => 'ฟิลด์ :attribute ต้องได้รับการยอมรับ',
    'accepted_if' => 'ฟิลด์ :attribute ต้องได้รับการยอมรับเมื่อ :other เป็น :value',
    'active_url' => 'ฟิลด์ :attribute ต้องเป็น URL ที่ถูกต้อง',
    'after' => 'ฟิลด์ :attribute ต้องเป็นวันที่หลังจาก :date',
    'after_or_equal' => 'ฟิลด์ :attribute ต้องเป็นวันที่หลังหรือเท่ากับ :date',
    'alpha' => 'ฟิลด์ :attribute ต้องประกอบด้วยตัวอักษรเท่านั้น',
    'alpha_dash' => 'ฟิลด์ :attribute ต้องประกอบด้วยตัวอักษร ตัวเลข เครื่องหมายขีด และขีดล่างเท่านั้น',
    'alpha_num' => 'ฟิลด์ :attribute ต้องประกอบด้วยตัวอักษรและตัวเลขเท่านั้น',
    'array' => 'ฟิลด์ :attribute ต้องเป็นอาร์เรย์',
    'ascii' => 'ฟิลด์ :attribute ต้องมีเฉพาะตัวอักษรและสัญลักษณ์แบบไบต์เดียว',
    'before' => 'ฟิลด์ :attribute ต้องเป็นวันที่ก่อน :date',
    'before_or_equal' => 'ฟิลด์ :attribute ต้องเป็นวันที่ก่อนหรือเท่ากับ :date',
    'between' => [
        'array' => 'ฟิลด์ :attribute ต้องมีระหว่าง :min และ :max รายการ',
        'file' => 'ฟิลด์ :attribute ต้องอยู่ระหว่าง :min และ :max กิโลไบต์',
        'numeric' => 'ฟิลด์ :attribute ต้องอยู่ระหว่าง :min และ :max',
        'string' => 'ฟิลด์ :attribute ต้องมีระหว่าง :min และ :max ตัวอักษร',
    ],
    'boolean' => 'ฟิลด์ :attribute ต้องเป็นจริงหรือเท็จ',
    'can' => 'ฟิลด์ :attribute มีค่าที่ไม่ได้รับอนุญาต',
    'confirmed' => 'การยืนยันฟิลด์ :attribute ไม่ตรงกัน',
    'contains' => 'ฟิลด์ :attribute ขาดค่าที่จำเป็น',
    'current_password' => 'รหัสผ่านไม่ถูกต้อง',
    'date' => 'ฟิลด์ :attribute ต้องเป็นวันที่ที่ถูกต้อง',
    'date_equals' => 'ฟิลด์ :attribute ต้องเป็นวันที่เท่ากับ :date',
    'date_format' => 'ฟิลด์ :attribute ต้องตรงกับรูปแบบ :format',
    'decimal' => 'ฟิลด์ :attribute ต้องมีทศนิยม :decimal ตำแหน่ง',
    'declined' => 'ฟิลด์ :attribute ต้องถูกปฏิเสธ',
    'declined_if' => 'ฟิลด์ :attribute ต้องถูกปฏิเสธเมื่อ :other เป็น :value',
    'different' => 'ฟิลด์ :attribute และ :other ต้องต่างกัน',
    'digits' => 'ฟิลด์ :attribute ต้องเป็นตัวเลข :digits หลัก',
    'digits_between' => 'ฟิลด์ :attribute ต้องอยู่ระหว่าง :min และ :max หลัก',
    'dimensions' => 'ฟิลด์ :attribute มีขนาดรูปภาพไม่ถูกต้อง',
    'distinct' => 'ฟิลด์ :attribute มีค่าซ้ำกัน',
    'doesnt_end_with' => 'ฟิลด์ :attribute ต้องไม่ลงท้ายด้วยหนึ่งในรายการต่อไปนี้: :values',
    'doesnt_start_with' => 'ฟิลด์ :attribute ต้องไม่เริ่มต้นด้วยหนึ่งในรายการต่อไปนี้: :values',
    'email' => 'ฟิลด์ :attribute ต้องเป็นที่อยู่อีเมลที่ถูกต้อง',
    'ends_with' => 'ฟิลด์ :attribute ต้องลงท้ายด้วยหนึ่งในรายการต่อไปนี้: :values',
    'enum' => 'ฟิลด์ :attribute ที่เลือกไม่ถูกต้อง',
    'exists' => 'ฟิลด์ :attribute มีในระบบแล้ว',
    'extensions' => 'ฟิลด์ :attribute ต้องมีนามสกุลไฟล์ต่อไปนี้: :values',
    'file' => 'ฟิลด์ :attribute ต้องเป็นไฟล์',
    'filled' => 'ฟิลด์ :attribute ต้องมีค่า',
    'gt' => [
        'array' => 'ฟิลด์ :attribute ต้องมีมากกว่า :value รายการ',
        'file' => 'ฟิลด์ :attribute ต้องมากกว่า :value กิโลไบต์',
        'numeric' => 'ฟิลด์ :attribute ต้องมากกว่า :value',
        'string' => 'ฟิลด์ :attribute ต้องมากกว่า :value ตัวอักษร',
    ],
    'gte' => [
        'array' => 'ฟิลด์ :attribute ต้องมีอย่างน้อย :value รายการ',
        'file' => 'ฟิลด์ :attribute ต้องมากกว่าหรือเท่ากับ :value กิโลไบต์',
        'numeric' => 'ฟิลด์ :attribute ต้องมากกว่าหรือเท่ากับ :value',
        'string' => 'ฟิลด์ :attribute ต้องมากกว่าหรือเท่ากับ :value ตัวอักษร',
    ],
    'hex_color' => 'ฟิลด์ :attribute ต้องเป็นสีฐานสิบหกที่ถูกต้อง',
    'image' => 'ฟิลด์ :attribute ต้องเป็นรูปภาพ',
    'in' => 'ฟิลด์ :attribute ที่เลือกไม่ถูกต้อง',
    'in_array' => 'ฟิลด์ :attribute ต้องมีอยู่ใน :other',
    'integer' => 'ฟิลด์ :attribute ต้องเป็นจำนวนเต็ม',
    'ip' => 'ฟิลด์ :attribute ต้องเป็นที่อยู่ IP ที่ถูกต้อง',
    'ipv4' => 'ฟิลด์ :attribute ต้องเป็นที่อยู่ IPv4 ที่ถูกต้อง',
    'ipv6' => 'ฟิลด์ :attribute ต้องเป็นที่อยู่ IPv6 ที่ถูกต้อง',
    'json' => 'ฟิลด์ :attribute ต้องเป็นสตริง JSON ที่ถูกต้อง',
    'list' => 'ฟิลด์ :attribute ต้องเป็นรายการ',
    'lowercase' => 'ฟิลด์ :attribute ต้องเป็นตัวพิมพ์เล็ก',
    'lt' => [
        'array' => 'ฟิลด์ :attribute ต้องมีน้อยกว่า :value รายการ',
        'file' => 'ฟิลด์ :attribute ต้องน้อยกว่า :value กิโลไบต์',
        'numeric' => 'ฟิลด์ :attribute ต้องน้อยกว่า :value',
        'string' => 'ฟิลด์ :attribute ต้องน้อยกว่า :value ตัวอักษร',
    ],
    'lte' => [
        'array' => 'ฟิลด์ :attribute ต้องมีไม่เกิน :value รายการ',
        'file' => 'ฟิลด์ :attribute ต้องน้อยกว่าหรือเท่ากับ :value กิโลไบต์',
        'numeric' => 'ฟิลด์ :attribute ต้องน้อยกว่าหรือเท่ากับ :value',
        'string' => 'ฟิลด์ :attribute ต้องน้อยกว่าหรือเท่ากับ :value ตัวอักษร',
    ],
    'mac_address' => 'ฟิลด์ :attribute ต้องเป็นที่อยู่ MAC ที่ถูกต้อง',
    'max' => [
        'array' => 'ฟิลด์ :attribute ต้องมีไม่เกิน :max รายการ',
        'file' => 'ฟิลด์ :attribute ต้องไม่เกิน :max กิโลไบต์',
        'numeric' => 'ฟิลด์ :attribute ต้องไม่เกิน :max',
        'string' => 'ฟิลด์ :attribute ต้องไม่เกิน :max ตัวอักษร',
    ],
    'max_digits' => 'ฟิลด์ :attribute ต้องไม่เกิน :max หลัก',
    'mimes' => 'ฟิลด์ :attribute ต้องเป็นไฟล์ประเภท: :values',
    'mimetypes' => 'ฟิลด์ :attribute ต้องเป็นไฟล์ประเภท: :values',
    'min' => [
        'array' => 'ฟิลด์ :attribute ต้องมีอย่างน้อย :min รายการ',
        'file' => 'ฟิลด์ :attribute ต้องมีอย่างน้อย :min กิโลไบต์',
        'numeric' => 'ฟิลด์ :attribute ต้องมีอย่างน้อย :min',
        'string' => 'ฟิลด์ :attribute ต้องมีอย่างน้อย :min ตัวอักษร',
    ],
    'min_digits' => 'ฟิลด์ :attribute ต้องมีอย่างน้อย :min หลัก',
    'missing' => 'ฟิลด์ :attribute ต้องหายไป',
    'missing_if' => 'ฟิลด์ :attribute ต้องหายไปเมื่อ :other เป็น :value',
    'missing_unless' => 'ฟิลด์ :attribute ต้องหายไปเว้นแต่ :other เป็น :value',
    'missing_with' => 'ฟิลด์ :attribute ต้องหายไปเมื่อ :values ปรากฏ',
    'missing_with_all' => 'ฟิลด์ :attribute ต้องหายไปเมื่อ :values ปรากฏทั้งหมด',
    'multiple_of' => 'ฟิลด์ :attribute ต้องเป็นหลายเท่าของ :value',
    'not_in' => 'ฟิลด์ :attribute ที่เลือกไม่ถูกต้อง',
    'not_regex' => 'รูปแบบฟิลด์ :attribute ไม่ถูกต้อง',
    'numeric' => 'ฟิลด์ :attribute ต้องเป็นตัวเลข',
    'password' => [
        'letters' => 'ฟิลด์ :attribute ต้องมีตัวอักษรอย่างน้อยหนึ่งตัว',
        'mixed' => 'ฟิลด์ :attribute ต้องมีตัวอักษรตัวใหญ่และตัวเล็กอย่างน้อยหนึ่งตัว',
        'numbers' => 'ฟิลด์ :attribute ต้องมีตัวเลขอย่างน้อยหนึ่งตัว',
        'symbols' => 'ฟิลด์ :attribute ต้องมีสัญลักษณ์อย่างน้อยหนึ่งตัว',
        'uncompromised' => ':attribute ที่ให้มาปรากฏในข้อมูลรั่วไหล โปรดเลือก :attribute อื่น',
    ],
    'present' => 'ฟิลด์ :attribute ต้องมีอยู่',
    'present_if' => 'ฟิลด์ :attribute ต้องมีอยู่เมื่อ :other เป็น :value',
    'present_unless' => 'ฟิลด์ :attribute ต้องมีอยู่เว้นแต่ :other เป็น :value',
    'present_with' => 'ฟิลด์ :attribute ต้องมีอยู่เมื่อ :values ปรากฏ',
    'present_with_all' => 'ฟิลด์ :attribute ต้องมีอยู่เมื่อ :values ปรากฏทั้งหมด',
    'prohibited' => 'ฟิลด์ :attribute ถูกห้าม',
    'prohibited_if' => 'ฟิลด์ :attribute ถูกห้ามเมื่อ :other เป็น :value',
    'prohibited_unless' => 'ฟิลด์ :attribute ถูกห้ามเว้นแต่ :other อยู่ใน :values',
    'prohibits' => 'ฟิลด์ :attribute ห้าม :other จากการมีอยู่',
    'regex' => 'รูปแบบฟิลด์ :attribute ไม่ถูกต้อง',
    'required' => ':attribute ไม่สามารถว่างเปล่าได้',
    'required_array_keys' => 'ฟิลด์ :attribute ต้องมีรายการสำหรับ: :values',
    'required_if' => 'ฟิลด์ :attribute เป็นสิ่งจำเป็นเมื่อ :other เป็น :value',
    'required_if_accepted' => 'ฟิลด์ :attribute เป็นสิ่งจำเป็นเมื่อ :other ถูกยอมรับ',
    'required_if_declined' => 'ฟิลด์ :attribute เป็นสิ่งจำเป็นเมื่อ :other ถูกปฏิเสธ',
    'required_unless' => 'ฟิลด์ :attribute เป็นสิ่งจำเป็นเว้นแต่ :other อยู่ใน :values',
    'required_with' => 'ฟิลด์ :attribute เป็นสิ่งจำเป็นเมื่อ :values ปรากฏ',
    'required_with_all' => 'ฟิลด์ :attribute เป็นสิ่งจำเป็นเมื่อ :values ปรากฏทั้งหมด',
    'required_without' => 'ฟิลด์ :attribute เป็นสิ่งจำเป็นเมื่อ :values ไม่ปรากฏ',
    'required_without_all' => 'ฟิลด์ :attribute เป็นสิ่งจำเป็นเมื่อไม่มี :values ปรากฏ',
    'same' => 'ฟิลด์ :attribute ต้องตรงกับ :other',
    'size' => [
        'array' => 'ฟิลด์ :attribute ต้องมี :size รายการ',
        'file' => 'ฟิลด์ :attribute ต้องมีขนาด :size กิโลไบต์',
        'numeric' => 'ฟิลด์ :attribute ต้องมีขนาด :size',
        'string' => 'ฟิลด์ :attribute ต้องมี :size ตัวอักษร',
    ],
    'starts_with' => 'ฟิลด์ :attribute ต้องเริ่มต้นด้วยหนึ่งในรายการต่อไปนี้: :values',
    'string' => 'ฟิลด์ :attribute ต้องเป็นสตริง',
    'timezone' => 'ฟิลด์ :attribute ต้องเป็นเขตเวลา',
    'unique' => ':attribute ถูกใช้งานไปแล้ว',
    'uploaded' => ':attribute อัปโหลดล้มเหลว',
    'uppercase' => 'ฟิลด์ :attribute ต้องเป็นตัวพิมพ์ใหญ่',
    'url' => 'ฟิลด์ :attribute ต้องเป็น URL ที่ถูกต้อง',
    'ulid' => 'ฟิลด์ :attribute ต้องเป็น ULID ที่ถูกต้อง',
    'uuid' => 'ฟิลด์ :attribute ต้องเป็น UUID ที่ถูกต้อง',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | ที่นี่คุณสามารถระบุข้อความการตรวจสอบที่กำหนดเองสำหรับแอตทริบิวต์โดยใช้
    | การตั้งชื่อบรรทัดตามรูปแบบ "attribute.rule" ซึ่งทำให้สามารถ
    | ระบุข้อความการตรวจสอบที่กำหนดเองสำหรับกฎแอตทริบิวต์ได้อย่างรวดเร็ว
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'predictions' => [
            'required' => 'กรุณาเลือกทีมที่ต้องการทายผล',
            'max' => 'ท่านสามารถทายผลการแข่งได้ไม่เกิน :value',
            'point-not-enough' => 'คะแนนของท่านไม่เพียงพอต้องการอีก :need',
        ],
        'newPassword' => [
            'confirmed' => 'ยืนยันรหัสผ่านไม่ถูกต้อง',
            'same' => 'ยืนยันรหัสผ่านไม่ถูกต้อง'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | บรรทัดภาษาต่อไปนี้ถูกใช้เพื่อสลับตำแหน่งแอตทริบิวต์ของเรา
    | ให้เป็นคำที่เข้าใจได้ง่ายขึ้น เช่น "E-Mail Address" แทนที่จะใช้ "email"
    | ซึ่งช่วยทำให้ข้อความของเราชัดเจนขึ้น
    |
    */

    'attributes' => [
        'nickName' => 'ชื่อเล่น',
        'fullName' => 'ชื่อ-นามสกุล',
        'phone' => 'เบอร์โทรศัพท์',
        'password' => 'รหัสผ่าน',
        'name' => 'ชื่อ',
        'country' => 'ประเทศ',
        'teamName' => 'ชื่อทีม',
        'matchDate' => 'วันที่แข่ง',
        'matchTime' => 'เวลาแข่ง',
        'leagueId' => 'ลีค',
        'homeTeam' => 'เจ้าบ้าน',
        'awayTeam' => 'ทีมเยือน',
        'rateType' => 'รูปแบบอัตราต่อรอง',
        'homeTeamRate' => 'อัตราเข้าบ้าน',
        'awayTeamRate' => 'อัตราทีมเยือน',
        'predictions' => 'ทายผลการแข่ง',
        'coinExpect' => 'จำนวนที่ต้องการ',
        'teamMatchResult' => 'ผลการแข่งขัน',
        'winType' => 'รูปแบบการชนะ',
        'home' => 'เจ้าบ้าน',
        'amount' => 'จำนวน',
        'selectedRate' => 'อัตราต่อรอง',
        'oldPassword' => 'รหัสผ่านเก่า',
        'newPassword' => 'รหัสผ่านใหม่',
        'confirmPassword' => 'ยืนยันรหัสผ่าน',
    ],

];

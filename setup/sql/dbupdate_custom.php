<#1>
<?php

//include_once('./Services/Migration/DBUpdate_3560/classes/class.ilDBUpdateNewObjectType.php');
//ilDBUpdateNewObjectType::addAdminNode('boka', 'Booking Pool Settings');

?>
<#2>
<?php
/*
if (!$ilDB->tableColumnExists('booking_reservation', 'parent_ref_id')) {
    $ilDB->addTableColumn('booking_reservation', 'parent_ref_id', array(
        "type" => "integer",
        "notnull" => false,
        "length" => 4
    ));
}
*/
?>
<#3>
<?php
/*
if (!$ilDB->tableColumnExists('crs_settings', 'crs_start')) {
    $ilDB->addTableColumn('crs_settings', 'crs_start', array(
        "type" => "integer",
        "notnull" => false,
        "length" => 4
    ));
}
if (!$ilDB->tableColumnExists('crs_settings', 'crs_end')) {
    $ilDB->addTableColumn('crs_settings', 'crs_end', array(
        "type" => "integer",
        "notnull" => false,
        "length" => 4
    ));
}
*/
?>
<#4>
<?php
/*
if (!$ilDB->tableColumnExists('crs_settings', 'leave_end')) {
    $ilDB->addTableColumn('crs_settings', 'leave_end', array(
        "type" => "integer",
        "notnull" => false,
        "length" => 4
    ));
}
*/
?>
<#5>
<?php
/*
if (!$ilDB->tableColumnExists('crs_settings', 'auto_wait')) {
    $ilDB->addTableColumn('crs_settings', 'auto_wait', array(
        "type" => "integer",
        "notnull" => true,
        "length" => 1,
        "default" => 0
    ));
}
*/
?>
<#6>
<?php
/*
if (!$ilDB->tableColumnExists('crs_settings', 'min_members')) {
    $ilDB->addTableColumn('crs_settings', 'min_members', array(
        "type" => "integer",
        "notnull" => false,
        "length" => 2
    ));
}
*/
?>
<#7>
<?php
//$ilCtrlStructureReader->getStructure();
?>
<#8>
<?php
/*
    if (!$ilDB->tableColumnExists('crs_settings', 'cancel_end_noti'))
    {
        $ilDB->addTableColumn('crs_settings', 'cancel_end_noti', array(
            "type" => "integer",
            "notnull" => false,
            "length" => 4
        ));
    }
 */
?>
<#9>
<?php
/*
if (!$ilDB->tableColumnExists('grp_settings', 'registration_min_members')) {
    $ilDB->addTableColumn('grp_settings', 'registration_min_members', array(
        "type" => "integer",
        "notnull" => false,
        "length" => 2
    ));
}
*/
?>
<#10>
<?php
/*
if (!$ilDB->tableColumnExists('grp_settings', 'leave_end')) {
    $ilDB->addTableColumn('grp_settings', 'leave_end', array(
        "type" => "integer",
        "notnull" => false,
        "length" => 4
    ));
}
*/
?>
<#11>
<?php
/*
    if (!$ilDB->tableColumnExists('grp_settings', 'cancel_end_noti'))
    {
        $ilDB->addTableColumn('grp_settings', 'cancel_end_noti', array(
            "type" => "integer",
            "notnull" => false,
            "length" => 4
        ));
    }
 */
?>
<#12>
<?php
/*
if (!$ilDB->tableColumnExists('event', 'reg_min_users')) {
    $ilDB->addTableColumn('event', 'reg_min_users', array(
        "type" => "integer",
        "notnull" => false,
        "length" => 2
    ));
}
*/
?>
<#13>
<?php
if (!$ilDB->tableColumnExists('obj_members', 'booking_status')) {
    $ilDB->addTableColumn(
        'obj_members',
        'booking_status',
        array(
            'type' => 'text',
            'notnull' => false,
            'length' => 255
        )
    );
}
if (!$ilDB->tableColumnExists('obj_members', 'participation_status')) {
    $ilDB->addTableColumn(
        'obj_members',
        'participation_status',
        array(
            'type' => 'text',
            'notnull' => false,
            'length' => 255,
            'default' => 'not_set'
        )
    );
}
?>
<#14>
<?php
if ($ilDB->tableColumnExists('adv_mdf_definition', 'field_values')) {
    $ilDB->modifyTableColumn(
        'adv_mdf_definition',
        'field_values',
        array(
            'type' => 'clob'
        )
    );
}
?>
<#15>
<?php
$ilDB->manipulateF(
    'UPDATE adv_mdf_definition SET field_type = %s WHERE field_type = %s',
    ['integer', 'integer'],
    [99, 9]
);
?>
<#16>
<?php
// BEGIN PATCH: 15.01.20 seminar date-duration
/*
if (!$ilDB->tableColumnExists('crs_settings', 'period_time_indication')) {
    $ilDB->addTableColumn(
        'crs_settings',
        'period_time_indication',
        [
            'type' => \ilDBConstants::T_INTEGER,
            'notnull' => true,
            'default' => 0
        ]
    );
}
*/
// END PATCH: 15.01.20 seminar date-duration
?>
<#17>
<?php
// BEGIN PATCH: 21.01.20 seminar date-duration
/*
if (!$ilDB->tableColumnExists('crs_settings', 'period_start')) {
    $ilDB->addTableColumn(
        'crs_settings',
        'period_start',
        [
            'type' => \ilDBConstants::T_TIMESTAMP,
            'notnull' => false,
            'default' => null
        ]
    );
    $ilDB->addTableColumn(
        'crs_settings',
        'period_end',
        [
            'type' => \ilDBConstants::T_TIMESTAMP,
            'notnull' => false,
            'default' => null
        ]
    );
}

$query = 'select obj_id, crs_start, crs_end from crs_settings where crs_start IS NOT NULL or crs_end IS NOT NULL';
$res = $ilDB->query($query);
while ($row = $res->fetchRow(\ilDBConstants::FETCHMODE_OBJECT)) {
    $dtstart = $dtend = null;
    if ($row->crs_start != null) {
        $start = new DateTime();
        $start->setTimezone(new DateTimeZone('UTC'));
        $start->setTimestamp((int) $row->crs_start);
        $dtstart = $start->format('Y-m-d');
    }
    if ($row->crs_end != null) {
        $end = new DateTime();
        $end->setTimezone(new DateTimeZone('UTC'));
        $end->setTimestamp((int) $row->crs_end);
        $dtend = $end->format('Y-m-d');
    }

        $query = 'update crs_settings set ' .
            'period_start = ' . $ilDB->quote($dtstart, \ilDBConstants::T_TIMESTAMP) . ', ' .
            'period_end = ' . $ilDB->quote($dtend, \ilDBConstants::T_TIMESTAMP) . ' ' .
            'where obj_id = ' . $ilDB->quote($row->obj_id, \ilDBConstants::T_INTEGER);
        $ilDB->manipulate($query);
    }
}
*/
// END PATCH: 21.01.20 seminar date-duration
?>
<#18>
<?php
/*
if (!$ilDB->tableExists("book_obj_use_book")) {
    $fields = array(
        "obj_id" => array(
            "type" => "integer",
            "notnull" => true,
            "length" => 4,
            "default" => 0
        ),
        "book_obj_id" => array(
            "type" => "integer",
            "notnull" => true,
            "length" => 4,
            "default" => 0
        )
    );
    $ilDB->createTable("book_obj_use_book", $fields);
}
*/
?>
<#19>
<?php
/*
$ilDB->addPrimaryKey("book_obj_use_book", array("obj_id", "book_obj_id"));
*/
?>
<#20>
<?php
/*
if (!$ilDB->tableColumnExists('booking_reservation', 'context_obj_id')) {
    $ilDB->addTableColumn(
        'booking_reservation',
        'context_obj_id',
        array(
            'type' => 'integer',
            'length' => 1,
            'notnull' => false,
            'default' => 0
        ));
}
*/
?>
<#21>
<?php
/*
$ilDB->dropTableColumn('booking_reservation', 'context_obj_id');

if (!$ilDB->tableColumnExists('booking_reservation', 'context_obj_id')) {
    $ilDB->addTableColumn(
        'booking_reservation',
        'context_obj_id',
        array(
            'type' => 'integer',
            'length' => 4,
            'notnull' => false,
            'default' => 0
        ));
}
*/
?>
<#22>
<?php
/*
$ilDB->renameTableColumn('book_obj_use_book', "book_obj_id", 'book_ref_id');
*/
?>
<#23>
<?php
/*
$boka_set = new ilSetting("boka");
$globalPoolRefId = $boka_set->get("default");

if (!$ilDB->tableExists('booking_preferences')) {
    $query = '
    SELECT od.obj_id, objr.ref_id
    FROM object_data od
    INNER JOIN object_reference objr ON objr.obj_id = od.obj_id
    INNER JOIN tree t ON t.child = objr.ref_id
    WHERE od.type = ' . $ilDB->quote('crs', 'text');

    $res = $ilDB->query($query);
    while ($row = $ilDB->fetchAssoc($res)) {
        $parentRefId = (int) $row['ref_id'];
        $contextObjId = (int) $row['obj_id'];

        $ilDB->manipulateF(
            'UPDATE booking_reservation SET context_obj_id = %s WHERE parent_ref_id = %s',
            ['integer', 'integer'],
            [$contextObjId, $parentRefId]
        );
    }

    if ($globalPoolRefId) {
        $query = "
        SELECT od.obj_id, " . ((int) $globalPoolRefId) . "
        FROM object_data od
        INNER JOIN object_reference objr ON objr.obj_id = od.obj_id
        INNER JOIN tree t ON t.child = objr.ref_id
        LEFT JOIN book_obj_use_book ON book_obj_use_book.obj_id = od.obj_id
        WHERE book_obj_use_book.obj_id IS NULL AND od.type = " . $ilDB->quote('crs', 'text');

        $ilDB->manipulate(
            'INSERT INTO book_obj_use_book (obj_id, book_ref_id)' .
            '(' . $query . ')'
        );
    }


    $query = "
    SELECT od.obj_id, 'cont_bookings', 1
    FROM object_data od
    INNER JOIN object_reference objr ON objr.obj_id = od.obj_id
    INNER JOIN tree t ON t.child = objr.ref_id
    LEFT JOIN container_settings
        ON container_settings.id = od.obj_id
        AND container_settings.keyword = " . $ilDB->quote('cont_bookings', 'text') . "
    WHERE container_settings.id IS NULL AND od.type = " . $ilDB->quote('crs', 'text');

    $ilDB->manipulate(
        'INSERT INTO container_settings (id, keyword, value)' .
        '(' . $query . ')'
    );
}
*/
?>
<#24>
<?php
/*
$setting = new ilSetting('book_patch_mig');
if (!(bool) $setting->get('asked_for_migration', false)) {
    echo "<pre>
        Dear Administrator,

        DO NOT REFRESH THIS PAGE UNLESS YOU HAVE READ THE FOLLOWING INSTRUCTIONS

        Did you check the 'Booking Pool for Courses' (see: https://docu.ilias.de/goto_docu_wiki_wpage_5722_1357.html) migration was successful?

        * There should be a record for every existing ILIAS course in table `book_obj_use_book`
        * There should be a record with `key` = 'cont_bookings' for every existing ILIAS course in table `container_settings`
        * The `context_obj_id` value (a course `obj_id`) of every existing course record in table `booking_reservation` should match the corresponding course `ref_id` value of the `parent_ref_id` field.


        If you try to rerun the update process, this warning will be skipped.

        Best regards,
        The Test Maintainers
    </pre>";

    $setting->set('asked_for_migration', 1);
    exit();
}

if ($ilDB->tableColumnExists('booking_reservation', 'parent_ref_id')) {
    $ilDB->dropTableColumn('booking_reservation', 'parent_ref_id');
}*/
?>
<#25>
<?php
/*$boka_set = new ilSetting("boka");
$boka_set->delete("default");*/
?>
<#26>
<?php
//$ilCtrlStructureReader->getStructure();
?>
<#27>
<?php
/*
if (!$ilDB->tableExists('adv_md_record_int')) {
    $ilDB->createTable('adv_md_record_int', [
        'record_id' => [
            'type' => ilDBConstants::T_INTEGER,
            'length' => 4,
            'notnull' => true
        ],
        'title' => [
            'type' => ilDBConstants::T_TEXT,
            'notnull' => false,
            'length' => 128
        ],
        'description' => [
            'type' => ilDBConstants::T_TEXT,
            'notnull' => false,
            'length' => 4000
        ],
        'lang_code' => [
            'type' => ilDBConstants::T_TEXT,
            'notnull' => true,
            'length' => 5
        ],
        'lang_default' => [
            'type' => ilDBConstants::T_INTEGER,
            'length' => 1,
            'notnull' => true
        ]
    ]);
    $ilDB->addPrimaryKey('adv_md_record_int', ['record_id', 'lang_code']);
}
*/
?>
<#28>
<?php
// none
?>
<#29>
<?php
/*
if (!$ilDB->tableExists('adv_md_field_int')) {
    $ilDB->createTable('adv_md_field_int', [
        'field_id' => [
            'type' => ilDBConstants::T_INTEGER,
            'length' => 4,
            'notnull' => true
        ],
        'title' => [
            'type' => ilDBConstants::T_TEXT,
            'notnull' => false,
            'length' => 128
        ],
        'description' => [
            'type' => ilDBConstants::T_TEXT,
            'notnull' => false,
            'length' => 4000
        ],
        'lang_code' => [
            'type' => ilDBConstants::T_TEXT,
            'notnull' => true,
            'length' => 5
        ],
        'lang_default' => [
            'type' => ilDBConstants::T_INTEGER,
            'length' => 1,
            'notnull' => true
        ]
    ]);
    $ilDB->addPrimaryKey('adv_md_field_int', ['field_id', 'lang_code']);
}
*/
?>
<#30>
<?php
/*
if ($ilDB->tableColumnExists('adv_md_record_int', 'lang_default')) {
    $ilDB->dropTableColumn('adv_md_record_int', 'lang_default');
}
*/
?>
<#31>
<?php
/*
if ($ilDB->tableColumnExists('adv_md_field_int', 'lang_default')) {
    $ilDB->dropTableColumn('adv_md_field_int', 'lang_default');
}
*/
?>
<#32>
<?php
/*
if (!$ilDB->tableColumnExists('adv_md_record','lang_default')) {
    $ilDB->addTableColumn('adv_md_record', 'lang_default', [
        'type' => 'text',
        'notnull' => false,
        'length' => 2,
        'default' => ''
    ]);

}
*/
?>
<#33>
<?php
/*
if (!$ilDB->tableExists('adv_md_values_ltext')) {
    $ilDB->createTable('adv_md_values_ltext', [
        'obj_id' => [
            'type' => 'integer',
            'length' => 4,
            'notnull' => true,
            'default' => 0
        ],
        'sub_type' => [
            'type' => 'text',
            'length' => 10,
            'notnull' => true,
            'default' => "-"
        ],
        'sub_id' => [
            'type' => 'integer',
            'length' => 4,
            'notnull' => true,
            'default' => 0
        ],
        'field_id' => [
            'type' => 'integer',
            'length' => 4,
            'notnull' => true,
            'default' => 0
        ],
        'value_index' => [
            'type' => ilDBConstants::T_TEXT,
            'length' => 16,
            'notnull' => true,
        ],
        'value' => [
            'type' => ilDBConstants::T_TEXT,
            'length' => 4000,
            'notnull' => false
        ]
    ]);

    $ilDB->addPrimaryKey('adv_md_values_ltext', array('obj_id', 'sub_type', 'sub_id', 'field_id', 'value_index'));
}
*/
?>
<#34>
<?php
/*
if (!$ilDB->tableExists('adv_md_values_enum')) {
    $ilDB->createTable('adv_md_values_enum', [
        'obj_id' => [
            'type' => 'integer',
            'length' => 4,
            'notnull' => true,
            'default' => 0
        ],
        'sub_type' => [
            'type' => 'text',
            'length' => 10,
            'notnull' => true,
            'default' => "-"
        ],
        'sub_id' => [
            'type' => 'integer',
            'length' => 4,
            'notnull' => true,
            'default' => 0
        ],
        'field_id' => [
            'type' => 'integer',
            'length' => 4,
            'notnull' => true,
            'default' => 0
        ],
        'disabled' => [
            'type' => 'integer',
            'length' => 1,
            'notnull' => true,
            'default' => 0
        ],
        'value_index' => [
            'type' => ilDBConstants::T_TEXT,
            'length' => 16,
            'notnull' => true,
        ]
    ]);

    $ilDB->addPrimaryKey('adv_md_values_enum', array('obj_id', 'sub_type', 'sub_id', 'field_id', 'value_index'));
}
*/
?>
<#35>
<?php
/*
$query = 'select field_id, field_type, field_values from adv_mdf_definition ' .
    'where field_type = 1  or field_type = 8 ';
$res = $ilDB->query($query);
while ($row = $res->fetchRow(ilDBConstants::FETCHMODE_OBJECT)) {
    $values = unserialize($row->field_values);
    if (!is_array($values)) {
        continue;
    }
    $options = $values;

    $query = 'select * from adv_md_values_text ' .
        'where field_id = ' . $ilDB->quote($row->field_id, ilDBConstants::T_INTEGER);
    $val_res = $ilDB->query($query);
    while ($val_row = $val_res->fetchRow(ilDBConstants::FETCHMODE_OBJECT)) {

        $query = 'select * from adv_md_values_enum ' .
            'where obj_id = ' . $ilDB->quote($val_row->obj_id, ilDBConstants::T_INTEGER) . ' ' .
            'and sub_id = ' . $ilDB->quote($val_row->sub_id, ilDBConstants::T_INTEGER) . ' ' .
            'and sub_type = ' . $ilDB->quote($val_row->sub_type, ilDBConstants::T_TEXT) . ' ' .
            'and field_id = ' . $ilDB->quote($val_row->field_id, ilDBConstants::T_INTEGER);
        $exists_res = $ilDB->query($query);
        if ($exists_res->numRows()) {
            //ilLoggerFactory::getLogger('root')->info('field_id: ' . $val_row->field_id . ' is already migrated');
            continue;
        }
        $current_values = [];
        if (strpos($val_row->value, '~|~') === 0) {
            // multi enum
            $current_values = explode('~|~', $val_row->value);
            array_pop($current_values);
            array_shift($current_values);

        } else {
            $current_values[] = (string) $val_row->value;
        }
        //ilLoggerFactory::getLogger('root')->dump($current_values);
        $positions = [];
        foreach ($current_values as $value) {
            if (!strlen(trim($value))) {
                continue;
            }
            $idx = array_search($value, $options);
            if ($idx === false) {
                continue;
            }
            $positions[] = $idx;
        }

        //ilLoggerFactory::getLogger('root')->dump($positions);
        foreach ($positions as $pos) {

            $query = 'insert into adv_md_values_enum (obj_id, sub_type, sub_id, field_id, value_index, disabled) ' .
                'values ( ' .
                $ilDB->quote($val_row->obj_id, ilDBConstants::T_INTEGER) . ', ' .
                $ilDB->quote($val_row->sub_type, ilDBConstants::T_TEXT) . ', ' .
                $ilDB->quote($val_row->sub_id, ilDBConstants::T_INTEGER) . ', ' .
                $ilDB->quote($val_row->field_id, ilDBConstants::T_INTEGER) . ', ' .
                $ilDB->quote($pos, ilDBConstants::T_INTEGER) . ', ' .
                $ilDB->quote($val_row->disabled, ilDBConstants::T_INTEGER)
                . ' ) ';
            $ilDB->query($query);
        }

    }
}
*/
?>
<#36>
<?php
/*
if (!$ilDB->tableExists('adv_mdf_enum')) {
    $ilDB->createTable('adv_mdf_enum', [
        'field_id' => [
            'type' => ilDBConstants::T_INTEGER,
            'length' => 4,
            'notnull' => true,
        ],
        'lang_code' => [
            'type' => ilDBConstants::T_TEXT,
            'notnull' => true,
            'length' => 5
        ],
        'idx' => [
            'type' => ilDBConstants::T_INTEGER,
            'length' => 4,
            'notnull' => true,
        ],
        'value' => [
            'type' => ilDBConstants::T_TEXT,
            'length' => 4000,
            'notnull' => true
        ]
    ]);
    $ilDB->addPrimaryKey('adv_mdf_enum', array('field_id', 'lang_code', 'idx'));
}
*/
?>
<#37>
<?php
/*
$query = 'select value from settings where  module = ' . $ilDB->quote('common', ilDBConstants::T_TEXT) . ' ' .
    'and keyword = ' . $ilDB->quote('language', ilDBConstants::T_TEXT);
$res = $ilDB->query($query);
$default = 'en';
while ($row  = $res->fetchRow(ilDBConstants::FETCHMODE_OBJECT)) {
    $default = $row->value;
}
$query = 'update adv_md_record set lang_default = ' . $ilDB->quote($default, ilDBConstants::T_TEXT) . ' ' .
    'where lang_default IS NULL';
$ilDB->query($query);
*/
?>
<#38>
<?php
/*
$query = 'select * from adv_md_record ';
$res = $ilDB->query($query);
while ($row = $res->fetchRow(ilDBConstants::FETCHMODE_OBJECT)) {
    $query = 'select * from adv_md_record_int ' .
        'where record_id = ' . $ilDB->quote($row->record_id, ilDBConstants::T_INTEGER) . ' ' .
        'and lang_code = ' . $ilDB->quote($row->lang_default, ilDBConstants::T_TEXT);
    $int_res = $ilDB->query($query);
    if ($int_res->numRows()) {
        continue;
    }
    $query = 'insert into adv_md_record_int (record_id, title, description, lang_code ) ' .
        'values ( ' .
        $ilDB->quote($row->record_id, ilDBConstants::T_INTEGER) . ', ' .
        $ilDB->quote($row->title, ilDBConstants::T_TEXT) . ', ' .
        $ilDB->quote($row->description, ilDBConstants::T_TEXT) . ', ' .
        $ilDB->quote($row->lang_default, ilDBConstants::T_TEXT) .
        ')' ;
    $ilDB->manipulate($query);
}
*/
?>
<#39>
<?php
/*
$query = 'select advf.field_id, lang_default, advf.title, advf.description from adv_mdf_definition advf ' .
    'join adv_md_record advr on advf.record_id = advr.record_id ';
$res = $ilDB->query($query);
while ($row = $res->fetchRow(ilDBConstants::FETCHMODE_OBJECT)) {
    $query = 'select * from adv_md_field_int ' .
        'where field_id = ' . $ilDB->quote($row->field_id, ilDBConstants::T_INTEGER) . ' ' .
        'and lang_code = ' . $ilDB->quote($row->lang_default, ilDBConstants::T_TEXT);
    $int_res = $ilDB->query($query);
    if ($int_res->numRows()) {
        continue;
    }
    $query = 'insert into adv_md_field_int (field_id, title, description, lang_code ) ' .
        'values ( ' .
        $ilDB->quote($row->field_id, ilDBConstants::T_INTEGER) . ', ' .
        $ilDB->quote($row->title, ilDBConstants::T_TEXT) . ', ' .
        $ilDB->quote($row->description, ilDBConstants::T_TEXT) . ', ' .
        $ilDB->quote($row->lang_default, ilDBConstants::T_TEXT) .
        ')' ;
    $ilDB->manipulate($query);
}
*/
?>
<#40>
<?php
/*
if (!$ilias7SetupDone) {
$query = 'select advf.record_id, field_id, field_values, lang_default from adv_mdf_definition advf ' .
    'join adv_md_record advr on advf.record_id = advr.record_id ' . ' ' .
    'where ( field_type = ' . $ilDB->quote(1, ilDBConstants::T_INTEGER) . ' or ' .
    'field_type = ' . $ilDB->quote(8, ilDBConstants::T_INTEGER) . ' ) ';

$res = $ilDB->query($query);
while ($row = $res->fetchRow(ilDBConstants::FETCHMODE_OBJECT)) {

    $values = unserialize($row->field_values);
    if (array_key_exists('options', $values)) {
        $idx = 0;
        foreach ($values['options'] as $option) {
            $query = 'insert into adv_mdf_enum (field_id, lang_code, idx, value ) ' .
                'values ( ' .
                $ilDB->quote($row->field_id, ilDBConstants::T_INTEGER) . ', ' .
                $ilDB->quote($row->lang_default, ilDBConstants::T_TEXT) . ', ' .
                $ilDB->quote($idx++, ilDBConstants::T_INTEGER) . ', ' .
                $ilDB->quote($option, ilDBConstants::T_TEXT).
                ' ) ';
            $ilDB->manipulate($query);
        }
    }
    if (array_key_exists('option_translations', $values)) {
        foreach ($values['option_translations'] as $lang => $options) {
            if ($lang == $row->lang_default) {
                continue;
            }
            $idx = 0;
            foreach ($options as $option) {
                $query = 'insert into adv_mdf_enum (field_id, lang_code, idx, value ) ' .
                    'values ( ' .
                    $ilDB->quote($row->field_id, ilDBConstants::T_INTEGER) . ', ' .
                    $ilDB->quote($lang, ilDBConstants::T_TEXT) . ', ' .
                    $ilDB->quote($idx++, ilDBConstants::T_INTEGER) . ', ' .
                    $ilDB->quote($option, ilDBConstants::T_TEXT).
                    ' ) ';
                $ilDB->manipulate($query);
            }
        }
    }
    if (
        !array_key_exists('options', $values) &&
        !array_key_exists('options_translations', $values) &&
        is_array($values)
    ) {
        $idx = 0;
        foreach ($values as $option) {
            $query = 'insert into adv_mdf_enum (field_id, lang_code, idx, value ) ' .
                'values ( ' .
                $ilDB->quote($row->field_id, ilDBConstants::T_INTEGER) . ', ' .
                $ilDB->quote($row->lang_default, ilDBConstants::T_TEXT) . ', ' .
                $ilDB->quote($idx++, ilDBConstants::T_INTEGER) . ', ' .
                $ilDB->quote($option, ilDBConstants::T_TEXT).
                ' ) ';
            $ilDB->manipulate($query);
        }
    }
}
*/
?>
<#41>
<?php
/*
if (!$ilDB->tableColumnExists('adv_md_values_ltext', 'disabled')) {
    $ilDB->addTableColumn(
        'adv_md_values_ltext',
        'disabled',
        [
            'type' => ilDBConstants::T_INTEGER,
            'notnull' => true,
            'length' => 1,
            'default' => 0
        ]
    );
}
*/
?>
<#42>
<?php
//$ilCtrlStructureReader->getStructure();
?>
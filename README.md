# Activity Entity Connector

Connect an activity with any other entity. By default, CiviCRM allows to connect
activities with contacts, but not other entities. To achieve the connection to
other entities a join table is used that has field that holds the target table
name (`entity_table`) and the ID of the entity (`entity_id`). Table names need
to be added to the custom group `activity_used_for`.

Optionally a connection can be typed (field `record_type_id`). Possible types
need to be added to the custom group `activity_entity_connection_type`.

Cascade deletion is implemented, so you do not need to take care of deleting
connections when deleting entities.

This extension provides an APIv4 DAO entity that offers some additional actions
to manage the connections:
[EntityActivity](Civi/Api4/EntityActivity.php).

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v8.1+
* CiviCRM 5.55+

## Installation (Web UI)

Learn more about installing CiviCRM extensions in the [CiviCRM Sysadmin Guide](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/).

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl activity-entity@https://github.com/systopia/activity-entity/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/systopia/activity-entity.git
cv en activity-entity
```

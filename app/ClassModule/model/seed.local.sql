SET NAMES 'utf8';

DELETE FROM `core_element`;
DELETE FROM `core_relation`;
DELETE FROM `core_diagram`;

SET @OLD_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE `core_element`;
TRUNCATE `core_relation`;
TRUNCATE `core_diagram`;
SET FOREIGN_KEY_CHECKS = @OLD_CHECKS;


INSERT INTO `core_element` (`id`, `name`, `project_id`, `type`) VALUES
	(1, 'Class 1', 1, 'class'),
	(2, 'Class 2', 1, 'class'),
	(3, 'Note', 1, 'note'),
	(4, 'Dolor', 1, 'class'),
	(5, 'Sit', 1, 'class');

INSERT INTO `class_class` (`id`) VALUES
	(1), (2), (4), (5);

INSERT INTO `core_note` (`id`, `text`) VALUES
	(3, "Lorem ipsum
Dolor sit amet");

INSERT INTO `core_diagram` (`id`, `name`, `project_id`, `type`) VALUES
	(1, 'Class diagram', 1, 'class');

INSERT INTO `core_placement` (`diagram_id`, `element_id`, `posX`, `posY`) VALUES
	(1, 1, 0, 100),
	(1, 2, 550, 0),
	(1, 3, 250, 250),
	(1, 4, 500, 150),
	(1, 5, 500, 250);

INSERT INTO `core_relation` (`id`, `name`, `start_id`, `end_id`, `type`) VALUES
	(1, '!!!', 3, 1, 'noteLink'),
	(2, '???', 3, 2, 'noteLink'),
	(3, 'is cool with', 1, 2, 'association'),
	(4, '', 2, 4, 'association'),
	(5, '', 4, 5, 'association'),
	(6, '', 5, 1, 'association'),
	(7, 'self', 4, 4, 'association');

INSERT INTO `class_association` (`id`, `direction`, `sourceRole`, `sourceMultiplicity`, `targetRole`, `targetMultiplicity`) VALUES
	(3, 'none', 'foo', '1..*', 'bar', '0..1'),
	(4, 'uni', '', '', '', ''),
	(5, 'bi', '', '', '', ''),
	(6, 'uni', '', '', '', ''),
	(7, 'bi', '', '', '', '');


INSERT INTO `class_attribute` (`id`, `class_id`, `visibility`, `name`, `type_id`, `type`, `multiplicity`, `defaultValue`, `derived`, `static`) VALUES
	(1, 2, 'public', 'setFoo', 1, NULL, '0..1', 'null', 0, 0),
	(2, 2, 'protected', 'getFoo', NULL, 'Foo', '', '""', 1, 0),
	(3, 2, 'private', 'food', NULL, NULL, '', '', 0, 1),
	(4, 2, 'package', 'drink', NULL, NULL, '', '', 1, 1);


INSERT INTO `class_operation` (`id`, `class_id`, `visibility`, `name`, `returnType_id`, `returnType`, `abstract`, `static`) VALUES
	(1, 1, 'public', 'setFoo', 1, NULL, 0, 0),
	(2, 1, 'protected', 'getFoo', NULL, 'Foo', 1, 0),
	(3, 1, 'private', 'food', NULL, NULL, 0, 1),
	(4, 1, 'package', 'drink', NULL, NULL, 1, 1);

INSERT INTO `class_operationParameter` (`id`, `operation_id`, `name`, `type_id`, `type`, `multiplicity`, `defaultValue`, `direction`) VALUES
	(1, 3, 'what', 2, NULL, '0..1', 'null', 'inout'),
	(2, 1, 'bar', NULL, 'string', '', '""', 'in');

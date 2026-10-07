-- Import this file after selecting/creating your database in phpMyAdmin.
-- The application can also create the tables automatically when config.php connects successfully.

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    type ENUM('Singly','Doubly','Circular') NOT NULL,
    description TEXT NOT NULL,
    code TEXT NOT NULL,
    input_hint VARCHAR(255) DEFAULT '',
    reference_pdf VARCHAR(120) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (username,password,role) VALUES
('admin', '$2y$12$EhhQ3bA5e3ryBO9LfROQQePQEVCo1B6ipS6oSEjJAeIo8RLiiGNRS', 'admin'),
('user', '$2y$12$DjNlSmUN7.zfWOrwpdhwdO5eFPPR2vAJP29dnVXy7fNNbdAgQkr5W', 'user')
ON DUPLICATE KEY UPDATE username=VALUES(username);

INSERT INTO activities (title, type, description, code, input_hint, reference_pdf) VALUES
('Singly Linked List - Insert at Beginning','Singly','Creates a singly linked list and inserts a new value at the beginning.','const values = input.values.map(Number);\nvalues.unshift(Number(input.newValue));\nreturn values.join(" -> ") + " -> NULL";','Existing values: 10,20,30 | New value: 5','singly_linked_list.pdf'),
('Singly Linked List - Delete by Value','Singly','Removes the first node containing the requested value.','const values = input.values.map(Number);\nconst target = Number(input.target);\nconst index = values.indexOf(target);\nif(index >= 0) values.splice(index,1);\nreturn (values.length ? values.join(" -> ") + " -> NULL" : "NULL") + (index >= 0 ? "" : " (value not found)");','Existing values: 10,20,30 | Delete: 20','singly_linked_list.pdf'),
('Doubly Linked List - Insert at End','Doubly','Adds a new node at the end and shows forward and backward traversal.','const values = input.values.map(Number);\nvalues.push(Number(input.newValue));\nreturn "Forward: " + values.join(" <-> ") + " <-> NULL\\nBackward: " + values.slice().reverse().join(" <-> ") + " <-> NULL";','Existing values: 10,20,30 | New value: 40','doubly_linked_list.pdf'),
('Doubly Linked List - Delete by Value','Doubly','Deletes the first matching value while preserving forward and backward links conceptually.','const values = input.values.map(Number);\nconst target = Number(input.target);\nconst index = values.indexOf(target);\nif(index >= 0) values.splice(index,1);\nreturn "Forward: " + (values.length ? values.join(" <-> ") : "NULL") + "\\nBackward: " + (values.length ? values.slice().reverse().join(" <-> ") : "NULL");','Existing values: 10,20,30 | Delete: 20','doubly_linked_list.pdf'),
('Circular Linked List - Insert at End','Circular','Adds a node at the end. The last node points back to the first node.','const values = input.values.map(Number);\nvalues.push(Number(input.newValue));\nreturn values.join(" -> ") + " -> (" + values[0] + ") [back to head]";','Existing values: 10,20,30 | New value: 40','circular_linked_list.pdf'),
('Circular Linked List - Delete by Value','Circular','Deletes the first matching value and demonstrates traversal back to the head.','const values = input.values.map(Number);\nconst target = Number(input.target);\nconst index = values.indexOf(target);\nif(index >= 0) values.splice(index,1);\nif(!values.length) return "List is empty";\nreturn values.join(" -> ") + " -> (" + values[0] + ") [back to head]";','Existing values: 10,20,30 | Delete: 20','circular_linked_list.pdf');

INSERT INTO activities (title,type,description,code,input_hint,reference_pdf) VALUES
('Singly Linked List - Treasure Hunt','Singly','An automatic treasure hunt that traverses nodes from HEAD to NULL while demonstrating all required singly linked-list operations.','','','singly_linked_list.pdf'),
('Doubly Linked List - Music Playlist','Doubly','A functional music playlist demonstrating forward and backward traversal plus all required doubly linked-list operations.','','','doubly_linked_list.pdf'),
('Circular Linked List - Hot Potato','Circular','An automatic Hot Potato turn game demonstrating circular traversal and all required circular linked-list operations.','','','circular_linked_list.pdf');

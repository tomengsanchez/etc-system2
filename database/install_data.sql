--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'student'),
(3, 'teacher');

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`) VALUES
(1, 'Admin User', 'admin@example.com', '$2y$10$9T8hU1Ybka63.xs7SL51kuX1w2QZVTQUxgD7nKbrG3ZQJnOz3OdTm', 1),
(2, 'Student User', 'student@example.com', '$2y$10$iusesomecrazystrings22/T5H5Gq.t6g2g.WvHLeH6Yg1nU1nU1', 2),
(3, 'Teacher User', 'teacher@example.com', '$2y$10$iusesomecrazystrings22/T5H5Gq.t6g2g.WvHLeH6Yg1nU1nU1', 3);

--
-- Dumping data for table `courses`
--

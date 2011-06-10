SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `jos_huruhelpdesk_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `note_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `type` text NOT NULL,
  `size` int(11) NOT NULL,
  `content` mediumblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jos_huruhelpdesk_categories` (
  `category_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cname` text NOT NULL,
  `rep_id` bigint(20) NOT NULL,
  UNIQUE KEY `category_id` (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jos_huruhelpdesk_categories_predeftext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `predeftext` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_category_id` (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jos_huruhelpdesk_config` (
  `id` int(11) NOT NULL,
  `hdreply` text NOT NULL,
  `hdurl` text NOT NULL,
  `notifyuser` int(11) NOT NULL,
  `enablekb` int(11) NOT NULL,
  `defaultpriority` bigint(20) NOT NULL,
  `defaultstatus` bigint(20) NOT NULL,
  `closestatus` bigint(20) NOT NULL,
  `allowanonymous` int(11) NOT NULL,
  `defaultlang` int(11) NOT NULL,
  `pagerpriority` int(11) NOT NULL,
  `userselect` int(11) NOT NULL,
  `version` text NOT NULL,
  `show_username` int(11) NOT NULL,
  `show_email` int(11) NOT NULL,
  `show_department` int(11) NOT NULL,
  `show_location` int(11) NOT NULL,
  `show_phone` int(11) NOT NULL,
  `show_category` int(11) NOT NULL,
  `show_status` int(11) NOT NULL,
  `show_priority` int(11) NOT NULL,
  `show_rep` int(11) NOT NULL,
  `show_timespent` int(11) NOT NULL,
  `set_username` int(11) NOT NULL,
  `set_email` int(11) NOT NULL,
  `set_department` int(11) NOT NULL,
  `set_location` int(11) NOT NULL,
  `set_phone` int(11) NOT NULL,
  `set_category` int(11) NOT NULL,
  `set_status` int(11) NOT NULL,
  `set_priority` int(11) NOT NULL,
  `set_rep` int(11) NOT NULL,
  `set_timespent` int(11) NOT NULL,
  `hdnotifyname` text NOT NULL,
  `defaultdepartment` bigint(20) NOT NULL,
  `defaultcategory` bigint(20) NOT NULL,
  `defaultrep` bigint(20) NOT NULL,
  `fileattach_allow` int(11) NOT NULL,
  `fileattach_allowedextensions` text NOT NULL,
  `fileattach_allowedmimetypes` text NOT NULL,
  `fileattach_maxsize` bigint(20) NOT NULL,
  `fileattach_type` int(11) NOT NULL,
  `fileattach_path` text NOT NULL,
  `fileattach_download` int(11) NOT NULL,
  `fileattach_maxage` int(11) NOT NULL,
  `notifyadminonnewcases` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jos_huruhelpdesk_departments` (
  `department_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dname` text NOT NULL,
  UNIQUE KEY `department_id` (`department_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jos_huruhelpdesk_emailmsg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `subject` text NOT NULL,
  `body` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jos_huruhelpdesk_langstrings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_id` bigint(20) NOT NULL,
  `variable` text NOT NULL,
  `langtext` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jos_huruhelpdesk_language` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `langname` text NOT NULL,
  `localized` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jos_huruhelpdesk_notes` (
  `note_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` bigint(20) NOT NULL,
  `note` longtext NOT NULL,
  `adddate` datetime NOT NULL,
  `uid` text NOT NULL,
  `ip` text,
  `priv` int(11) NOT NULL,
  PRIMARY KEY (`note_id`),
  FULLTEXT KEY `note` (`note`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jos_huruhelpdesk_priority` (
  `priority_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pname` text NOT NULL,
  UNIQUE KEY `priority_id` (`priority_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jos_huruhelpdesk_problems` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` text NOT NULL,
  `uemail` text NOT NULL,
  `ulocation` text NOT NULL,
  `uphone` text NOT NULL,
  `rep` bigint(20) NOT NULL,
  `status` bigint(20) NOT NULL,
  `time_spent` bigint(20) NOT NULL,
  `category` bigint(20) NOT NULL,
  `close_date` datetime NOT NULL,
  `department` bigint(20) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `solution` text NOT NULL,
  `start_date` datetime NOT NULL,
  `priority` bigint(20) NOT NULL,
  `entered_by` bigint(20) NOT NULL,
  `kb` bigint(20) NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `rep` (`rep`,`status`,`category`,`department`,`priority`),
  FULLTEXT KEY `solution` (`solution`),
  FULLTEXT KEY `description` (`description`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jos_huruhelpdesk_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` bigint(20) NOT NULL,
  `sname` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jos_huruhelpdesk_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `joomla_id` int(11) NOT NULL,
  `isuser` int(11) NOT NULL,
  `isrep` int(11) NOT NULL,
  `isadmin` int(11) NOT NULL,
  `phone` text NOT NULL,
  `pageraddress` text NOT NULL,
  `phonemobile` text NOT NULL,
  `phonehome` text NOT NULL,
  `location1` text NOT NULL,
  `location2` text NOT NULL,
  `department` bigint(20) NOT NULL,
  `language` bigint(20) NOT NULL,
  `viewreports` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `joomla_id` (`joomla_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__huruhelpdesk_config`
--

INSERT IGNORE INTO `#__huruhelpdesk_config` (`id`, `hdreply`, `hdurl`, `notifyuser`, `enablekb`, `defaultpriority`, `defaultstatus`, `closestatus`, `allowanonymous`, `defaultlang`, `pagerpriority`, `userselect`, `version`, `show_username`, `show_email`, `show_department`, `show_location`, `show_phone`, `show_category`, `show_status`, `show_priority`, `show_rep`, `show_timespent`, `set_username`, `set_email`, `set_department`, `set_location`, `set_phone`, `set_category`, `set_status`, `set_priority`, `set_rep`, `set_timespent`, `hdnotifyname`, `defaultdepartment`, `defaultcategory`, `defaultrep`, `fileattach_allow`, `fileattach_allowedextensions`, `fileattach_allowedmimetypes`, `fileattach_maxsize`, `fileattach_type`, `fileattach_path`, `fileattach_download`, `fileattach_maxage`, `notifyadminonnewcases`) VALUES
(1, 'helpdesk@domain.com', 'http://server.domain.com/', 1, 1, 3, 15, 24, 0, 1, 10, 1, '0.88 beta j16', 0, 0, 10000, 10000, 10000, 0, 0, 0, 0, 10000, 0, 0, 10000, 10000, 10000, 0, 50, 50, 50, 50, 'Ticket-System', 0, 14, -1, 25, '.jpg,.png,.gif,.zip', 'image/jpeg,image/png', 1048576, 1, '', 25, 0, '');

--
-- Dumping data for table `#__huruhelpdesk_emailmsg`
--

INSERT IGNORE INTO `#__huruhelpdesk_emailmsg` (`id`, `type`, `subject`, `body`) VALUES
(1, 'repclose', 'HELPDESK: Problem [problemid] Closed', 'The following problem has been closed.  You can view the problem at [url]\r\n\r\nPROBLEM DETAILS\r\n---------------\r\nID: [problemid]\r\nUser: [uid]\r\nDate: [startdate]\r\nTitle: [title]\r\nPriority: [priority]\r\nCategory: [category]\r\n\r\nSOLUTION\r\n--------\r\n[solution]'),
(2, 'repnew', 'HELPDESK: Problem [problemid] Assigned', 'The following problem has been assigned to you.  You can update the problem at [url]\r\n\r\nPROBLEM DETAILS\r\n---------------\r\nID: [problemid]\r\nDate: [startdate]\r\nTitle: [title]\r\nPriority: [priority]\r\nCategory: [category]\r\n\r\nUSER INFORMATION\r\n----------------\r\nUsername: [uid]\r\nEmail: [uemail]\r\nPhone: [phone]\r\nLocation: [location]\r\nDepartment: [department]\r\n\r\nDESCRIPTION\r\n-----------\r\n[description]'),
(3, 'reppager', 'HELPDESK: Problem [problemid] Assigned/Updated', 'Title:[title]\r\nUser:[uid]\r\nPriority:[priority]'),
(4, 'repupdate', 'HELPDESK: Problem [problemid] Updated', 'The following problem has been updated.  You can view the problem at [url]\r\n\r\nPROBLEM DETAILS\r\n---------------\r\nID: [problemid]\r\nUser: [uid]\r\nDate: [startdate]\r\nTitle: [title]\r\n\r\nDESCRIPTION\r\n-----------\r\n[description]\r\n\r\nNOTES\r\n-----------\r\n[notes]'),
(5, 'userclose', 'HELPDESK: Problem [problemid] Closed', 'Your help desk problem has been closed.  You can view the solution below or at: [url]\r\n\r\nPROBLEM DETAILS\r\n---------------\r\nID: [problemid]\r\nUser: [uid]\r\nDate: [startdate]\r\nTitle: [title]\r\n\r\nSOLUTION\r\n--------\r\n[solution]'),
(6, 'usernew', 'HELPDESK: Problem [problemid] Created', 'Thank you for submitting your problem to the help desk.  You can view or update the problem at: [url]\r\n\r\nPROBLEM DETAILS\r\n---------------\r\nID: [problemid]\r\nUser: [uid]\r\nDate: [startdate]\r\nTitle: [title]\r\n\r\nDESCRIPTION\r\n-----------\r\n[description]'),
(7, 'userupdate', 'HELPDESK: Problem [problemid] Updated', 'Your help desk problem has been updated.  You can view the problem at: [url]\r\n\r\nPROBLEM DETAILS\r\n---------------\r\nID: [problemid]\r\nUser: [uid]\r\nDate: [startdate]\r\nTitle: [title]\r\n\r\nDESCRIPTION\r\n-----------\r\n[description]\r\n\r\nNOTES\r\n-----------\r\n[notes]');

--
-- Dumping data for table `#__huruhelpdesk_langstrings`
--

INSERT IGNORE INTO `#__huruhelpdesk_langstrings` (`id`, `lang_id`, `variable`, `langtext`) VALUES
(54, 1, 'Classification', 'Classification'),
(59, 1, 'Close', 'Close'),
(60, 1, 'CloseDate', 'Close Date'),
(72, 1, 'ContactInformation', 'Contact Information'),
(79, 1, 'DateSubmitted', 'Entered'),
(91, 1, 'Department', 'Department'),
(95, 1, 'Description', 'Description'),
(104, 1, 'EditInformation', 'Edit your contact information'),
(108, 1, 'EMail', 'E-Mail'),
(109, 1, 'EmailAddress', 'E-Mail Address'),
(120, 1, 'EndDate', 'End Date'),
(121, 1, 'EnterAdditionalNotes', 'Enter Additional Notes'),
(125, 1, 'EnteredBy', 'Entered By'),
(126, 1, 'EnterinKnowledgeBase', 'Enter in Knowledge Base'),
(135, 1, 'From', 'From'),
(142, 1, 'HideFromEndUser', 'Hide new note from end user'),
(144, 1, 'ID', 'ID'),
(146, 1, 'In', 'In'),
(148, 1, 'InOutBoard', 'View In/Out Board'),
(174, 1, 'Location', 'Location'),
(191, 1, 'minutes', 'minutes'),
(199, 1, 'NewProblem', 'New Problem'),
(207, 1, 'Noresultsfound', 'No matching problems found'),
(210, 1, 'Notes', 'Notes'),
(218, 1, 'OpenProblems', 'Open problems'),
(219, 1, 'OpenProblemsfor', 'Open problems for'),
(222, 1, 'Or', 'Or'),
(225, 1, 'Out', 'Out'),
(237, 1, 'Phone', 'Phone'),
(248, 1, 'Priority', 'Priority'),
(257, 1, 'ProblemID', 'View Problem #'),
(259, 1, 'ProblemInformation', 'Problem Information'),
(263, 1, 'Problems', 'Problems'),
(277, 1, 'ReopenProblem', 'Reopen'),
(278, 1, 'Rep', 'Rep'),
(282, 1, 'Reports', 'Reports'),
(286, 1, 'Required', 'Required'),
(292, 1, 'Save', 'Save'),
(294, 1, 'Search', 'Search'),
(296, 1, 'SearchFields', 'Search Fields'),
(297, 1, 'SearchProblems', 'Search problems'),
(298, 1, 'SearchResults', 'Search Results'),
(299, 1, 'SearchtheKnowledgeBase', 'Search the knowledgebase'),
(302, 1, 'SelectCategory', 'Select Category'),
(303, 1, 'SelectDepartment', 'Select Department'),
(304, 1, 'SelectUser', 'Select User'),
(31, 1, 'AssignedTo', 'Assigned To'),
(313, 1, 'Solution', 'Solution'),
(317, 1, 'StartDate', 'Start Date'),
(319, 1, 'Status', 'Status'),
(329, 1, 'Subject', 'Subject'),
(330, 1, 'Submit', 'Submit'),
(332, 1, 'SubmitNewProblem', 'Submit new problem'),
(335, 1, 'SupportRep', 'Support Rep'),
(352, 1, 'Time', 'Time'),
(353, 1, 'TimeSpent', 'Time Spent'),
(354, 1, 'Title', 'Title'),
(356, 1, 'Total', 'Total'),
(373, 1, 'User', 'User'),
(376, 1, 'UserName', 'User Name'),
(385, 1, 'View', 'View'),
(386, 1, 'ViewProblemList', 'View all open problems'),
(387, 1, 'Viewproblemsfor', 'View open problems assigned to'),
(394, 1, 'ViewAssignedProblems', 'View assigned problems'),
(395, 1, 'ViewSubmittedProblems', 'View submitted problems'),
(397, 1, 'ViewProblemsFromLast', 'View all problems from last'),
(398, 1, 'days', 'days'),
(399, 1, 'Activity', 'Activity'),
(400, 1, 'Home', 'Home'),
(401, 1, 'Refresh', 'Refresh'),
(402, 1, 'NoLimit', '(No limit)'),
(403, 1, 'Back', 'Back'),
(404, 1, 'ProblemNumber', 'Problem #'),
(405, 1, 'ProblemSaved', 'Problem saved'),
(406, 1, 'ErrorSavingProblem', 'Error saving record: Invalid or missing required fields.'),
(409, 1, 'DefaultRep', 'Default Rep'),
(410, 1, 'NotFound', 'No matching problem found'),
(411, 1, 'EnterVerification', 'Verification'),
(412, 1, 'Name', 'Full Name'),
(413, 1, 'Admin', 'Admin'),
(414, 1, 'ShowReps', 'Show Reps'),
(415, 1, 'ShowAll', 'Show All'),
(416, 1, 'RepsAdmins', 'Reps & Admins Only'),
(417, 1, 'AllUsers', 'All Huru Users'),
(418, 1, 'SearchCriteria', 'Search Criteria'),
(419, 1, 'Reset', 'Reset'),
(421, 1, 'To', 'To'),
(422, 1, 'SearchText', 'Search Text'),
(423, 1, 'Browse', 'Browse'),
(424, 1, 'Cancel', 'Cancel'),
(425, 1, 'NewSearch', 'New Search'),
(426, 1, 'Results', 'Results'),
(427, 1, 'ProblemsFound', 'problem(s) found'),
(428, 1, 'EnterSearch', 'Enter your search criteria & click the Search button'),
(429, 1, 'EnterReport', 'Enter your report criteria & click the View button'),
(430, 1, 'AvailableReports', 'Available Reports'),
(431, 1, 'DateRange', 'Date Range'),
(432, 1, 'AverageTime', 'Average Time'),
(433, 1, 'PercentProblemTotal', '% of Problems'),
(434, 1, 'PercentTimeTotal', '% of Time'),
(435, 1, 'min', 'min'),
(436, 1, 'Unknown', 'Unknown'),
(437, 1, 'ActivitySummary', 'MIS Activity Summary'),
(438, 1, 'Modified', 'Modified'),
(439, 1, 'through', 'through'),
(440, 1, 'MailProblemID', 'Problem Id Number'),
(441, 1, 'MailTitle', 'Problem Title/Subject'),
(442, 1, 'MailDescription', 'Problem Description'),
(443, 1, 'MailUID', 'Username of person who reported problem'),
(444, 1, 'MailUEmail', 'Email address of person who reported problem'),
(445, 1, 'MailPhone', 'Phone number of person who reported problem'),
(446, 1, 'MailLocation', 'Location of person who reported problem'),
(447, 1, 'MailDepartment', 'Department of person who reported problem'),
(448, 1, 'MailPriority', 'Priority of problem'),
(449, 1, 'MailCategory', 'Category of problem'),
(45, 1, 'Category', 'Category'),
(450, 1, 'MailStartDate', 'Date problem was reported/opened'),
(451, 1, 'MailURL', 'URL to problem'),
(452, 1, 'MailSolution', 'Problem solution'),
(453, 1, 'MailNotes', 'Notes about problem'),
(454, 1, 'ProblemsSubmittedBy', 'Problems submitted by'),
(456, 1, 'for', 'for'),
(457, 1, 'ForPrevious', 'for previous'),
(458, 1, 'All', 'All'),
(459, 1, 'OpenProblemsLC', 'open problems'),
(460, 1, 'Print', 'Print'),
(461, 1, 'UserProfile', 'User Profile'),
(462, 1, 'JoomlaUserInfo', 'Joomla! user information'),
(463, 1, 'HuruUserInfo', 'Helpdesk User Profile'),
(464, 1, 'HomePhone', 'Home Phone'),
(465, 1, 'MobilePhone', 'Mobile Phone'),
(466, 1, 'PagerAddress', 'Pager Address'),
(467, 1, 'Location1', 'Location 1'),
(468, 1, 'Location2', 'Location 2'),
(469, 1, 'Language', 'Language'),
(470, 1, 'ManageCategories', 'Manage Categories'),
(471, 1, 'EditCategory', 'Edit Category'),
(472, 1, 'CategoryName', 'Category Name'),
(473, 1, 'Default', 'Default'),
(474, 1, 'GeneralConfiguration', 'General Configuration'),
(475, 1, 'ReplyAddress', 'Reply Address'),
(476, 1, 'BaseURL', 'Helpdesk Base URL'),
(477, 1, 'NotifyUserOnCaseUpdate', 'Notify User on Case Update'),
(478, 1, 'AllowAnonymousCases', 'Allow Anonymous Cases'),
(479, 1, 'AllowUserSelectOnNewCases', 'Allow User Select on New Cases'),
(480, 1, 'KnowledgeBaseViewAuthority', 'Knowledgebase View Authority'),
(481, 1, 'Disable', 'Disable'),
(482, 1, 'RepsOnly', 'Reps Only'),
(483, 1, 'UsersAndReps', 'Users & Reps'),
(484, 1, 'Anyone', 'Anyone'),
(485, 1, 'DefaultPriority', 'Default Priority'),
(486, 1, 'PagerPriority', 'Pager Priority'),
(487, 1, 'DefaultStatus', 'Default Status'),
(488, 1, 'ClosedStatus', 'Closed Status'),
(489, 1, 'DefaultLanguage', 'Default Language'),
(490, 1, 'EmailMessages', 'Email Messages'),
(491, 1, 'Users', 'Users'),
(492, 1, 'Departments', 'Departments'),
(493, 1, 'Categories', 'Categories'),
(494, 1, 'Priorities', 'Priorities'),
(495, 1, 'Statuses', 'Statuses'),
(496, 1, 'Languages', 'Languages'),
(497, 1, 'About', 'About'),
(498, 1, 'Administration', 'Administration'),
(499, 1, 'ManageDepartments', 'Manage Departments'),
(500, 1, 'DepartmentName', 'Department Name'),
(501, 1, 'EditDepartment', 'Edit Department'),
(502, 1, 'ManageEmailMessages', 'Manage Email Messages'),
(503, 1, 'Type', 'Type'),
(504, 1, 'Body', 'Body'),
(505, 1, 'Edit...', 'Edit...'),
(506, 1, 'EditEmailMessage', 'Edit Email Message'),
(507, 1, 'AvailableSubstitutions', 'Available Substitutions'),
(508, 1, 'ManageLanguages', 'Manage Languages'),
(509, 1, 'LanguageName', 'Language Name'),
(510, 1, 'Localized', 'Localized'),
(511, 1, 'EditLanguage', 'Edit Language'),
(512, 1, 'LanguageStrings', 'Language Strings'),
(513, 1, 'ManagePriorities', 'Manage Priorities'),
(514, 1, 'PriorityName', 'Priority Name'),
(515, 1, 'EditPriority', 'Edit Priority'),
(516, 1, 'ManageStatuses', 'Manage Statuses'),
(517, 1, 'Rank', 'Rank'),
(518, 1, 'StatusName', 'Status Name'),
(519, 1, 'EditStatus', 'Edit Status'),
(520, 1, 'ManageLanguageStrings', 'Manage Language Strings'),
(521, 1, 'LanguageID', 'Language ID'),
(522, 1, 'Variable', 'Variable'),
(523, 1, 'Text', 'Text'),
(524, 1, 'EditString', 'Edit String'),
(525, 1, 'ManageUsers', 'Manage Users'),
(526, 1, 'HuruID', 'Huru ID'),
(527, 1, 'JoomlaID', 'Joomla! ID'),
(528, 1, 'SyncJoomlaUsers', 'Sync Joomla! Users'),
(529, 1, 'SyncJoomlaUsersConfirm', 'This will synchronize the Huru users table with the Joomla! users table - importing accounts into Huru as necessary.  No Joomla! user accounts will be altered.'),
(530, 1, 'EditUser', 'Edit User'),
(531, 1, 'IsUser', 'User'),
(532, 1, 'IsRep', 'Rep'),
(533, 1, 'IsAdmin', 'Administrator'),
(534, 1, 'ViewReports', 'View Reports'),
(535, 1, 'UserSuperAdminNote', '(Note: This setting is ignored for Joomla! Super Administrators - who are always Huru Helpdesk Admins)'),
(536, 1, 'DefaultAssignment', 'Category Default'),
(537, 1, 'PageTitle', 'Huru Helpdesk'),
(538, 1, 'SelectOverride', 'Overrides contact info above'),
(539, 1, 'CannotDeleteClosedStatus', 'Cannot delete status set as Closed Status in General Confguration'),
(540, 1, 'Go', 'Go'),
(541, 1, 'ProblemDeleted', 'Problem deleted'),
(542, 1, 'ProblemNotDeleted', 'Error deleting problem'),
(543, 1, 'DeleteProblem', 'Delete Problem #'),
(544, 1, 'Delete', 'Delete'),
(545, 1, 'ProblemCreated', 'Problem created'),
(546, 1, 'AttachFileToNote', 'Attach file to note'),
(547, 1, 'Attachment', 'Attachment'),
(548, 1, 'AttachmentFileNoteFound', 'Attachment file not found'),
(549, 1, 'DefaultFileAttachmentNote', 'File attached'),
(550, 1, 'ErrorSavingAttachment', 'Error saving attachment'),
(551, 1, 'NotImplemented', 'Not implemented'),
(552, 1, 'FileTypeNotAllowed', 'File type not allowed'),
(553, 1, 'FileTooLarge', 'File too large'),
(554, 1, 'UnknownError', 'Unknown error'),
(555, 1, 'NotificationSenderName', 'Notification Sender Name'),
(556, 1, 'AllowFileAttachments', 'Allow File Attachments to Cases'),
(557, 1, 'AllowedAttachmentExtensions', 'Allowed Attachment Extensions'),
(558, 1, 'ExtensionExample', 'Comma separated list of allowed file extensions (with leading periods) [e.g: .jpg,.png,.txt]'),
(559, 1, 'MaximumAttachmentSize', 'Maximum Attachment Size'),
(560, 1, 'Bytes', 'bytes'),
(561, 1, 'AttachmentSizeWarning', 'Huru maximum is 16MB.  PHP may be configured for less.  Check your php.ini file'),
(562, 1, 'AttachmentDownloadPermissions', 'Allow attachment download'),
(563, 1, 'AttachmentDeleted', 'Attachment deleted'),
(564, 1, 'AttachmentNotDeleted', 'Error deleting attachment'),
(565, 1, 'MaximumAttachmentAge', 'Auto-purge old attachments after'),
(566, 1, 'SetToZero', 'Set to 0 to disable auto-purge'),
(567, 1, 'MailFullname', 'Full name of user who entered case (from Joomla account)'),
(568, 1, 'NotifyAdminOnNewCase', 'Email address to notify for all new cases'),
(569, 1, 'LeaveBlank', 'Leave blank to disable'),
(570, 1, 'Notifications', 'Notifications'),
(571, 1, 'Permissions', 'Permissions'),
(572, 1, 'Defaults', 'Defaults'),
(573, 1, 'FileAttachments', 'File Attachments'),
(574, 1, 'DisplayedFields', 'Displayed Fields'),
(575, 1, 'DefaultDepartment', 'Default Department'),
(576, 1, 'DefaultCategory', 'Default Category'),
(577, 1, 'Show', 'Show'),
(578, 1, 'Set', 'Set'),
(579, 1, 'IfNotSetable', 'If not visible/setable by everyone, a default for this field must be set above'),
(580, 1, 'Updated', 'Updated'),
(581, 2, 'Classification', 'Klassifikation'),
(582, 2, 'Close', 'Schlie�en'),
(583, 2, 'CloseDate', 'Datum der Schlie�ung'),
(584, 2, 'ContactInformation', 'Kontakt-Infos'),
(585, 2, 'DateSubmitted', 'Eingetragen am'),
(586, 2, 'Department', 'Abteilung'),
(587, 2, 'Description', 'Beschreibung'),
(588, 2, 'EditInformation', 'Kontakt-Info bearbeiten'),
(589, 2, 'EMail', 'eMail'),
(590, 2, 'EmailAddress', 'eMail-Adresse'),
(591, 2, 'EndDate', 'Enddatum'),
(592, 2, 'EnterAdditionalNotes', 'Notiz eingeben'),
(593, 2, 'EnteredBy', 'Eingetragen von'),
(594, 2, 'EnterinKnowledgeBase', 'In KnowledgeBase eintragen'),
(595, 2, 'From', 'Von'),
(596, 2, 'HideFromEndUser', 'Notiz vor User verbergen'),
(597, 2, 'ID', 'ID'),
(598, 2, 'In', 'In'),
(599, 2, 'InOutBoard', 'Online User ansehen'),
(600, 2, 'Location', 'Ort'),
(601, 2, 'minutes', 'Minuten'),
(602, 2, 'NewProblem', 'Neues Ticket'),
(603, 2, 'Noresultsfound', 'Kein passendes Ticket gefunden'),
(604, 2, 'Notes', 'Notizen'),
(605, 2, 'OpenProblems', 'Offene Tickets'),
(606, 2, 'OpenProblemsfor', 'Offene Tickets f�r'),
(607, 2, 'Or', 'Oder'),
(608, 2, 'Out', 'Out'),
(609, 2, 'Phone', 'Telefon'),
(610, 2, 'Priority', 'Priorit�t'),
(611, 2, 'ProblemID', 'Zeige Ticket #'),
(612, 2, 'ProblemInformation', 'Problem-Informationen'),
(613, 2, 'Problems', 'Probleme'),
(614, 2, 'ReopenProblem', 'Wiederer�ffnen'),
(615, 2, 'Rep', 'Bearbeiter'),
(616, 2, 'Reports', 'Reports'),
(617, 2, 'Required', 'Ben�tigt'),
(618, 2, 'Save', 'Speichern'),
(619, 2, 'Search', 'Suchen'),
(620, 2, 'SearchFields', 'Suchfelder'),
(621, 2, 'SearchProblems', 'Suche Tickets'),
(622, 2, 'SearchResults', 'Suchergebnisse'),
(623, 2, 'SearchtheKnowledgeBase', 'Wissensdatenbank durchsuchen'),
(624, 2, 'SelectCategory', 'Kategorie ausw�hlen'),
(625, 2, 'SelectDepartment', 'Abteilung ausw�hlen'),
(626, 2, 'SelectUser', 'Nutzer ausw�hlen'),
(627, 2, 'AssignedTo', 'Zugewiesen an'),
(628, 2, 'Solution', 'L�sung'),
(629, 2, 'StartDate', 'Start-Datum'),
(630, 2, 'Status', 'Status'),
(631, 2, 'Subject', 'Betreff'),
(632, 2, 'Submit', 'Absenden'),
(633, 2, 'SubmitNewProblem', 'Neues Ticket er�ffnen'),
(634, 2, 'SupportRep', 'Bearbeiter'),
(635, 2, 'Time', 'Zeit'),
(636, 2, 'TimeSpent', 'ben�tigte Zeit'),
(637, 2, 'Title', 'Titel'),
(638, 2, 'Total', 'Gesamt'),
(639, 2, 'User', 'Nutzer'),
(640, 2, 'UserName', 'Nutzername'),
(641, 2, 'View', 'Anzeigen'),
(642, 2, 'ViewProblemList', 'Alle offenen Tickets anzeigen'),
(643, 2, 'Viewproblemsfor', 'Offene Tickets anzeigen von'),
(644, 2, 'ViewAssignedProblems', 'Zugwiesene Tickets anzeigen'),
(645, 2, 'ViewSubmittedProblems', 'Tickets anzeigen'),
(646, 2, 'ViewProblemsFromLast', 'Alle Tickets anzeigen seit'),
(647, 2, 'days', 'Tage'),
(648, 2, 'Activity', 'Aktivit�t'),
(649, 2, 'Home', 'Startseite'),
(650, 2, 'Refresh', 'Aktualisieren'),
(651, 2, 'NoLimit', '(Kein Limit)'),
(652, 2, 'Back', 'Zur�ck'),
(653, 2, 'ProblemNumber', 'Ticket #'),
(654, 2, 'ProblemSaved', 'Ticket gespeichert'),
(655, 2, 'ErrorSavingProblem', 'Fehler beim Speichern: Ung�ltiges oder fehlendes ben�tigtes Feld.'),
(656, 2, 'DefaultRep', 'Standard-Bearbeiter'),
(657, 2, 'NotFound', 'Kein passendes Ticket gefunden'),
(658, 2, 'EnterVerification', 'Verifizierung'),
(659, 2, 'Name', 'Name'),
(660, 2, 'Admin', 'Admin'),
(661, 2, 'ShowReps', 'Bearbeiter anzeigen'),
(662, 2, 'ShowAll', 'Alle zeigen'),
(663, 2, 'RepsAdmins', 'nur Bearbeiter & Admins'),
(664, 2, 'AllUsers', 'Alle Nutzer'),
(665, 2, 'SearchCriteria', 'Suchkriterien'),
(666, 2, 'Reset', 'Zur�cksetzen'),
(667, 2, 'To', 'An'),
(668, 2, 'SearchText', 'Suchtext'),
(669, 2, 'Browse', 'Durchbl�ttern'),
(670, 2, 'Cancel', 'Abbrechen'),
(671, 2, 'NewSearch', 'Neue Suche'),
(672, 2, 'Results', 'Ergebnisse'),
(673, 2, 'ProblemsFound', 'Ticket(s) gefunden'),
(674, 2, 'EnterSearch', 'Suchkriterien eingeben und auf Suche klicken'),
(675, 2, 'EnterReport', 'Reportkriterien eingeben und auf Anzeigen klicken'),
(676, 2, 'AvailableReports', 'Verf�gbare Reports'),
(677, 2, 'DateRange', 'Zeitintervall'),
(678, 2, 'AverageTime', 'Durchschnittliche Zeit'),
(679, 2, 'PercentProblemTotal', '% der Tickets'),
(680, 2, 'PercentTimeTotal', '% der Zeit'),
(681, 2, 'min', 'min'),
(682, 2, 'Unknown', 'Unbekannt'),
(683, 2, 'ActivitySummary', 'Zusammenfassung'),
(684, 2, 'Modified', 'Ge�ndert'),
(685, 2, 'through', 'durch'),
(686, 2, 'MailProblemID', 'Ticket-ID'),
(687, 2, 'MailTitle', 'Betreff'),
(688, 2, 'MailDescription', 'Beschreibung'),
(689, 2, 'MailUID', 'Nutzername des Meldenden'),
(690, 2, 'MailUEmail', 'eMail-Adresse des Meldenden'),
(691, 2, 'MailPhone', 'Telefonnummer des Meldenden'),
(692, 2, 'MailLocation', 'Ort des Meldenden'),
(693, 2, 'MailDepartment', 'Abteilung des Meldenden'),
(694, 2, 'MailPriority', 'Priorit�t'),
(695, 2, 'MailCategory', 'Kategorie'),
(696, 2, 'Category', 'Kategorie'),
(697, 2, 'MailStartDate', 'Er�ffnungsdatum'),
(698, 2, 'MailURL', 'URL zum Ticket'),
(699, 2, 'MailSolution', 'L�sung'),
(700, 2, 'MailNotes', 'Notizen zum Problem'),
(701, 2, 'ProblemsSubmittedBy', 'Tickets von'),
(702, 2, 'for', 'f�r'),
(703, 2, 'ForPrevious', 'f�r Vorhergehende'),
(704, 2, 'All', 'Alles'),
(705, 2, 'OpenProblemsLC', 'offene Tickets'),
(706, 2, 'Print', 'Drucken'),
(707, 2, 'UserProfile', 'Nutzerprofil'),
(708, 2, 'JoomlaUserInfo', 'Joomla!-Profil'),
(709, 2, 'HuruUserInfo', 'Helpdesk-Profil'),
(710, 2, 'HomePhone', 'Telefon'),
(711, 2, 'MobilePhone', 'Mobiltelefon'),
(712, 2, 'PagerAddress', 'Pagernummer'),
(713, 2, 'Location1', 'Location 1'),
(714, 2, 'Location2', 'Location 2'),
(715, 2, 'Language', 'Sprache'),
(716, 2, 'ManageCategories', 'Kategorien verwalten'),
(717, 2, 'EditCategory', 'Kategorie bearbeiten'),
(718, 2, 'CategoryName', 'Kategoriebezeichnung'),
(719, 2, 'Default', 'Standard'),
(720, 2, 'GeneralConfiguration', 'Allgemeine Einstellungen'),
(721, 2, 'ReplyAddress', 'Antwortadresse'),
(722, 2, 'BaseURL', 'Helpdesk-URL'),
(723, 2, 'NotifyUserOnCaseUpdate', 'Nutzer bei Aktualisierung benachrichtigen'),
(724, 2, 'AllowAnonymousCases', 'Anonyme Tickets zulassen'),
(725, 2, 'AllowUserSelectOnNewCases', 'Nutzer darf bei neuen Tickets Nutzernamen w�hlen'),
(726, 2, 'KnowledgeBaseViewAuthority', 'Darf Wissensdatenbank benutzen'),
(727, 2, 'Disable', 'Deaktivieren'),
(728, 2, 'RepsOnly', 'Nur Bearbeiter'),
(729, 2, 'UsersAndReps', 'Nutzer & Bearbeiter'),
(730, 2, 'Anyone', 'Jeder'),
(731, 2, 'DefaultPriority', 'Standard-Priorit�t'),
(732, 2, 'PagerPriority', 'Pager-Priorit�t'),
(733, 2, 'DefaultStatus', 'Standard-Status'),
(734, 2, 'ClosedStatus', 'Geschlossen'),
(735, 2, 'DefaultLanguage', 'Standardsprache'),
(736, 2, 'EmailMessages', 'eMails'),
(737, 2, 'Users', 'Nutzer'),
(738, 2, 'Departments', 'Abteilungen'),
(739, 2, 'Categories', 'Kategorien'),
(740, 2, 'Priorities', 'Priorit�ten'),
(741, 2, 'Statuses', 'Stati'),
(742, 2, 'Languages', 'Sprachen'),
(743, 2, 'About', '�ber'),
(744, 2, 'Administration', 'Administration'),
(745, 2, 'ManageDepartments', 'Abteilungen verwalten'),
(746, 2, 'DepartmentName', 'Abteilungsbezeichnung'),
(747, 2, 'EditDepartment', 'Abteilung bearbeiten'),
(748, 2, 'ManageEmailMessages', 'eMails verwalten'),
(749, 2, 'Type', 'Typ'),
(750, 2, 'Body', 'Text'),
(751, 2, 'Edit...', 'Bearbeiten...'),
(752, 2, 'EditEmailMessage', 'eMail bearbeiten'),
(753, 2, 'AvailableSubstitutions', 'verf�gbare Ersetzungen'),
(754, 2, 'ManageLanguages', 'Sprachen verwalten'),
(755, 2, 'LanguageName', 'Sprachbezeichnung (Englisch)'),
(756, 2, 'Localized', 'Sprachbezeichnung'),
(757, 2, 'EditLanguage', 'Sprache bearbeiten'),
(758, 2, 'LanguageStrings', 'Sprachvariablen'),
(759, 2, 'ManagePriorities', 'Priorit�ten verwalten'),
(760, 2, 'PriorityName', 'Priorit�tsbezeichnung'),
(761, 2, 'EditPriority', 'Priorit�t bearbeiten'),
(762, 2, 'ManageStatuses', 'Stati verwalten'),
(763, 2, 'Rank', 'Rang'),
(764, 2, 'StatusName', 'Statusbezeichnung'),
(765, 2, 'EditStatus', 'Status bearbeiten'),
(766, 2, 'ManageLanguageStrings', 'Sprachvariablen verwalten'),
(767, 2, 'LanguageID', 'Sprach-ID'),
(768, 2, 'Variable', 'Variable'),
(769, 2, 'Text', 'Text'),
(770, 2, 'EditString', 'Text bearbeiten'),
(771, 2, 'ManageUsers', 'Nutzer bearbeiten'),
(772, 2, 'HuruID', 'Huru-ID'),
(773, 2, 'JoomlaID', 'Joomla!-ID'),
(774, 2, 'SyncJoomlaUsers', 'Mit Joomla! synchronisieren'),
(775, 2, 'SyncJoomlaUsersConfirm', 'Importiert die Joomla!-Nutzer in Huru Helpdesk. An den Joomla!-Tabellen wird keine �nderung vorgenommen.'),
(776, 2, 'EditUser', 'Nutzer bearbeiten'),
(777, 2, 'IsUser', 'Nutzer'),
(778, 2, 'IsRep', 'Bearbeiter'),
(779, 2, 'IsAdmin', 'Administrator'),
(780, 2, 'ViewReports', 'Reports ansehen'),
(781, 2, 'UserSuperAdminNote', '(Bemerkung: Diese Einstellung wird f�r Joomla! Super-Administratoren ignoriert. Diese sind immer auch Huru Helpdesk-Admins).'),
(782, 2, 'DefaultAssignment', 'Standard der Kategorie'),
(783, 2, 'PageTitle', 'Rising Gods Ticket-System'),
(784, 2, 'SelectOverride', '�berschreibt obige Kontaktinformationen'),
(785, 2, 'CannotDeleteClosedStatus', 'Der geschlossen-Status kann nicht gel�scht werden'),
(786, 2, 'Go', 'Go'),
(787, 2, 'ProblemDeleted', 'Ticket gel�scht'),
(788, 2, 'ProblemNotDeleted', 'Fehler beim L�schen des Tickets'),
(789, 2, 'DeleteProblem', 'L�sche Ticket #'),
(790, 2, 'Delete', 'L�schen'),
(791, 2, 'ProblemCreated', 'Ticket erstellt'),
(792, 2, 'AttachFileToNote', 'Datei anh�ngen'),
(793, 2, 'Attachment', 'Dateianhang'),
(794, 2, 'AttachmentFileNoteFound', 'Dateianhang nicht gefunden'),
(795, 2, 'DefaultFileAttachmentNote', 'Datei angeh�ngt'),
(796, 2, 'ErrorSavingAttachment', 'Fehler beim Speichern des Dateianhangs'),
(797, 2, 'NotImplemented', 'Nicht implementiert'),
(798, 2, 'FileTypeNotAllowed', 'Dateityp nicht erlaubt'),
(799, 2, 'FileTooLarge', 'Datei zu gro�'),
(800, 2, 'UnknownError', 'Unbekannter Fehler'),
(801, 2, 'NotificationSenderName', 'Absender der Benachrichtigung'),
(802, 2, 'AllowFileAttachments', 'Dateianh�nge erlauben'),
(803, 2, 'AllowedAttachmentExtensions', 'Erlaubte Dateiendungen'),
(804, 2, 'ExtensionExample', 'Komma-getrennte Liste erlaubter Dateiendungen (mit f�hrendem Punkt) [z.B: .jpg,.png,.txt]'),
(805, 2, 'MaximumAttachmentSize', 'Maximale Anhang-Gr��e'),
(806, 2, 'Bytes', 'bytes'),
(807, 2, 'AttachmentSizeWarning', 'Das Maximum betr�gt 16MB.  PHP k�nnte aber auf weniger eingestellt sein (siehe PHP.ini).'),
(808, 2, 'AttachmentDownloadPermissions', 'Herunterladen von Dateianh�ngen erlauben'),
(809, 2, 'AttachmentDeleted', 'Dateianhang gel�scht'),
(810, 2, 'AttachmentNotDeleted', 'Dateianhang l�schen fehlgeschlagen'),
(811, 2, 'MaximumAttachmentAge', 'L�sche Dateianh�nge automatisch nach'),
(812, 2, 'SetToZero', 'Auf 0 setzen um Auto-L�schung zu deaktivieren.'),
(813, 2, 'MailFullname', 'Joomla!-Name des Ticket-Erstellers'),
(814, 2, 'NotifyAdminOnNewCase', 'Benachrichtigung f�r alle Tickets an'),
(815, 2, 'LeaveBlank', 'Freilassen um zu deaktivieren'),
(816, 2, 'Notifications', 'Benachrichtigungen'),
(817, 2, 'Permissions', 'Rechte'),
(818, 2, 'Defaults', 'Standards'),
(819, 2, 'FileAttachments', 'Dateianh�nge'),
(820, 2, 'DisplayedFields', 'Angezeigte Spalten'),
(821, 2, 'DefaultDepartment', 'Standardabteilung'),
(822, 2, 'DefaultCategory', 'Standardkategorie'),
(823, 2, 'Show', 'Anzeigen'),
(824, 2, 'Set', 'Setzen'),
(825, 2, 'IfNotSetable', 'Wenn nicht sichbar/setzbar f�r Jeden wird oben ein Standardwert f�r dieses Feld ben�tigt.'),
(826, 2, 'Updated', 'Aktualisiert'),
(827, 1, 'Assigned', 'Assigned'),
(828, 2, 'Assigned', 'Zugewiesen'),
(829, 1, 'Predeftexts', 'Categories: Predefined Texts'),
(830, 2, 'Predeftexts', 'Kategorien: vordefinierte Texte'),
(831, 1, 'Predeftext', 'predefined text'),
(832, 2, 'Predeftext', 'vordefinierter Text');

--
-- Dumping data for table `#__huruhelpdesk_language`
--

INSERT IGNORE INTO `#__huruhelpdesk_language` (`id`, `langname`, `localized`) VALUES
(1, 'English', 'English'),
(2, 'German', 'Deutsch');

--
-- Dumping data for table `#__huruhelpdesk_priority`
--

INSERT IGNORE INTO `#__huruhelpdesk_priority` (`priority_id`, `pname`) VALUES
(6, ' 6 - VERY HIGH '),
(5, ' 5 - HIGH '),
(4, ' 4 - ELEVATED '),
(3, ' 3 - NORMAL '),
(2, ' 2 - LOW '),
(1, '1 - VERY LOW'),
(10, ' 10 - EMERGENCY - PAGE '),
(9, ' 9 - EMERGENCY - NO PAGE ');

--
-- Dumping data for table `#__huruhelpdesk_status`
--

INSERT IGNORE INTO `#__huruhelpdesk_status` (`id`, `status_id`, `sname`) VALUES
(22, 65, 'TESTING'),
(21, 63, 'WAITING'),
(20, 60, 'HOLD'),
(19, 55, 'ESCALATED'),
(18, 50, 'IN PROGRESS'),
(17, 20, 'OPEN'),
(16, 10, 'RECEIVED'),
(15, 1, 'NEW'),
(24, 100, 'CLOSED');

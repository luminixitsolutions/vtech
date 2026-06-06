<?php

/**
 * Sidebar-aligned menu access tree with separate checkbox per sub-screen.
 */
function menuAccessTreeItem($id, $label)
{
    return ['id' => (int) $id, 'label' => (string) $label];
}

function menuAccessTreeGroup($label, array $items)
{
    return ['group' => (string) $label, 'items' => $items];
}

function menuAccessDetailedTreeCollectIds(array $entries)
{
    $ids = [];
    foreach ($entries as $entry) {
        if (isset($entry['group'])) {
            foreach ($entry['items'] as $item) {
                $id = (int) ($item['id'] ?? 0);
                if ($id > 0) {
                    $ids[$id] = $id;
                }
            }
        } else {
            $id = (int) ($entry['id'] ?? 0);
            if ($id > 0) {
                $ids[$id] = $id;
            }
        }
    }

    return array_values($ids);
}

/** @return array<string, array<int, array<string, mixed>>> */
function getMenuAccessDetailedTree()
{
    return [
        'MPUVNL Management' => [
            menuAccessTreeItem(93, 'Dispatched Calling Confirmation'),
            menuAccessTreeItem(94, 'Before Installation Calling'),
            menuAccessTreeItem(95, 'After Installation Calling'),
            menuAccessTreeItem(96, 'Before Inspection Calling'),
            menuAccessTreeItem(118, 'Beneficiary Selection / MPUVNL Portal'),
        ],
        'Lead Management' => [
            menuAccessTreeItem(64, 'Upload Excel'),
            menuAccessTreeGroup('Lead Creation', [
                menuAccessTreeItem(188, 'Lead Irrigation'),
                menuAccessTreeItem(44, 'Lead Creation'),
            ]),
            menuAccessTreeItem(45, 'View Leads'),
            menuAccessTreeItem(46, 'Lead Assign'),
            menuAccessTreeItem(47, 'To do Activity'),
            menuAccessTreeItem(48, 'Appointment Scheduling'),
            menuAccessTreeItem(63, 'Prospects Customers'),
            menuAccessTreeItem(49, 'Opportunity'),
            menuAccessTreeItem(50, 'Quotation'),
            menuAccessTreeItem(51, 'Opportunity Convert to Order'),
            menuAccessTreeItem(52, 'Social Media Marketing'),
        ],
        'Pump Application Management' => [
            menuAccessTreeGroup('Pump Application Form', [
                menuAccessTreeItem(189, 'Upload Excel'),
                menuAccessTreeItem(190, 'All Application Form'),
                menuAccessTreeItem(191, 'Pending Application Form'),
                menuAccessTreeItem(192, 'Approve Application Form'),
                menuAccessTreeItem(193, 'Reject Application Form'),
            ]),
            menuAccessTreeGroup('Assign Application Form', [
                menuAccessTreeItem(194, 'Assign Application Form'),
                menuAccessTreeItem(195, 'Assigned Application Form'),
            ]),
            menuAccessTreeItem(151, 'To Do Activity'),
            menuAccessTreeItem(152, 'Prospects Applications'),
            menuAccessTreeItem(153, 'Applications Convert To Order'),
        ],
        'Dealer Lead Management' => [
            menuAccessTreeItem(154, 'Lead Creation'),
            menuAccessTreeItem(155, 'View Leads'),
            menuAccessTreeItem(156, 'Lead Assign'),
            menuAccessTreeItem(157, 'To do Activity'),
            menuAccessTreeItem(158, 'Prospects Dealers'),
            menuAccessTreeItem(159, 'Convert to Dealer'),
        ],
        'Master Management' => [
            menuAccessTreeGroup('Locations', [
                menuAccessTreeItem(196, 'Country'),
                menuAccessTreeItem(197, 'State'),
                menuAccessTreeItem(198, 'City'),
            ]),
            menuAccessTreeItem(56, 'PI/Quotation Products'),
            menuAccessTreeItem(2, 'Store'),
            menuAccessTreeItem(3, 'Issues'),
            menuAccessTreeItem(4, 'Scheme/Yojna'),
            menuAccessTreeItem(5, 'User Type'),
            menuAccessTreeItem(140, 'Project Head'),
            menuAccessTreeItem(141, 'Project Sub Head'),
            menuAccessTreeItem(6, 'Pump Head'),
            menuAccessTreeItem(7, 'Pump Capacity'),
            menuAccessTreeItem(72, 'Pump Outlet Size'),
            menuAccessTreeItem(73, 'Standard Depth'),
            menuAccessTreeItem(74, 'Pump Head Range'),
            menuAccessTreeItem(75, 'Module Watt'),
            menuAccessTreeItem(76, 'Module Qty'),
            menuAccessTreeItem(77, 'Structure'),
            menuAccessTreeItem(97, 'Module Make'),
            menuAccessTreeItem(98, 'Structure Make'),
            menuAccessTreeItem(8, 'Water Source'),
            menuAccessTreeItem(9, 'Type Of Pump'),
            menuAccessTreeItem(12, 'Bore Dia'),
            menuAccessTreeItem(13, 'Customer Type'),
            menuAccessTreeItem(34, 'Insurance Agency'),
            menuAccessTreeItem(15, 'Insurance Claim Reason'),
            menuAccessTreeItem(16, 'Insurance Claim Status'),
            menuAccessTreeItem(53, 'Lead Source'),
            menuAccessTreeItem(54, 'Lead Status'),
            menuAccessTreeItem(89, 'Dispatched Calling Confirmation Ques'),
            menuAccessTreeItem(90, 'Before Installation Ques'),
            menuAccessTreeItem(91, 'After Installation Ques'),
            menuAccessTreeItem(92, 'Before Inspection Ques'),
            menuAccessTreeItem(117, 'Beneficiary Selection Ques'),
        ],
        'Product Management' => [
            menuAccessTreeGroup('Product', [
                menuAccessTreeItem(199, 'Add Product'),
                menuAccessTreeItem(200, 'View Product'),
            ]),
            menuAccessTreeItem(17, 'BOS Product Specification'),
            menuAccessTreeItem(78, 'Structure Product Specification'),
        ],
        'User Accounts' => [
            menuAccessTreeGroup('Customers', [
                menuAccessTreeItem(201, 'Add Pump Customer'),
                menuAccessTreeItem(202, 'Pump Customers'),
            ]),
            menuAccessTreeItem(122, 'Upload Customers Excel'),
            menuAccessTreeItem(113, 'Agency Account'),
            menuAccessTreeGroup('Manufacture', [
                menuAccessTreeItem(203, 'Add Manufacture'),
                menuAccessTreeItem(204, 'View Manufactures'),
            ]),
            menuAccessTreeGroup('Company', [
                menuAccessTreeItem(205, 'Add Company'),
                menuAccessTreeItem(206, 'View Company'),
            ]),
            menuAccessTreeGroup('Employee', [
                menuAccessTreeItem(207, 'Add Employee'),
                menuAccessTreeItem(208, 'View Employee'),
            ]),
            menuAccessTreeGroup('Store Incharge Account', [
                menuAccessTreeItem(209, 'Create Store Incharge'),
                menuAccessTreeItem(210, 'View Store Incharge'),
            ]),
            menuAccessTreeGroup('Dispatch Officer Account', [
                menuAccessTreeItem(211, 'Add Dispatch Officer'),
                menuAccessTreeItem(212, 'View Dispatch Officer'),
            ]),
            menuAccessTreeGroup('Contractor', [
                menuAccessTreeItem(213, 'Add Contractor'),
                menuAccessTreeItem(214, 'View Contractor'),
                menuAccessTreeItem(215, 'Set Contractor Commission'),
            ]),
            menuAccessTreeItem(142, 'Contractor Billing Report'),
            menuAccessTreeGroup('Installer', [
                menuAccessTreeItem(216, 'Add Installer'),
                menuAccessTreeItem(217, 'View Installer'),
            ]),
            menuAccessTreeGroup('Driver Account', [
                menuAccessTreeItem(218, 'Add Driver Account'),
                menuAccessTreeItem(219, 'View Driver Account'),
            ]),
            menuAccessTreeGroup('Dealer', [
                menuAccessTreeItem(220, 'Add Dealer'),
                menuAccessTreeItem(221, 'View Dealer'),
            ]),
            menuAccessTreeGroup('Agency', [
                menuAccessTreeItem(222, 'Add Agency'),
                menuAccessTreeItem(223, 'View Agency'),
            ]),
            menuAccessTreeItem(129, 'Contractor Account'),
        ],
        'Assign Customers' => [
            menuAccessTreeItem(55, 'Assign Pump Customers To Co-ordinator'),
            menuAccessTreeItem(79, 'Assign Pump Customers To Field Survey'),
        ],
        'Tentative Production Plan' => [
            menuAccessTreeItem(130, 'BOS Production Plan'),
            menuAccessTreeItem(131, 'Structure Production Plan'),
        ],
        'Pump Survey' => [
            menuAccessTreeItem(80, 'Pump Co-ordinator Survey'),
            menuAccessTreeItem(81, 'Pump Field Survey'),
        ],
        'Final Production Plan' => [
            menuAccessTreeItem(132, 'BOS Production Plan'),
            menuAccessTreeItem(133, 'Structure Production Plan'),
        ],
        'Under Production Beneficiary' => [
            menuAccessTreeItem(134, 'Under Production Beneficiary'),
            menuAccessTreeItem(224, 'Done beneficiary — required stock'),
        ],
        'PDI Verification' => [
            menuAccessTreeGroup('PDI Verification', [
                menuAccessTreeItem(225, 'Upload PDI Excel'),
                menuAccessTreeItem(226, 'View Uploaded PDI'),
                menuAccessTreeItem(227, 'Match PDI'),
            ]),
        ],
        'Purchase Order' => [
            menuAccessTreeGroup('Purchase Order', [
                menuAccessTreeItem(228, 'Add Purchase Order'),
                menuAccessTreeItem(229, 'View Purchase Order'),
                menuAccessTreeItem(230, 'Delete Bill No Stock'),
            ]),
            menuAccessTreeItem(27, 'Quotation'),
        ],
        'DCR Verification' => [
            menuAccessTreeGroup('DCR Verification', [
                menuAccessTreeItem(231, 'Upload DCR Excel'),
                menuAccessTreeItem(232, 'View Uploaded DCR'),
            ]),
        ],
        'Store Incharge Assignment' => [
            menuAccessTreeItem(58, 'Assign Beneficiary To Store'),
            menuAccessTreeItem(59, 'Approve By Store Incharge'),
            menuAccessTreeGroup('Assign Items To Store', [
                menuAccessTreeItem(233, 'Assign Items'),
                menuAccessTreeItem(234, 'View Assign Items'),
            ]),
        ],
        'Dispatch Officer Assignment' => [
            menuAccessTreeItem(60, 'Assign Beneficiary To Dispatch Officer'),
        ],
        'Assign Items To Dispatch Officer' => [
            menuAccessTreeGroup('Assign Items To Dispatch Officer', [
                menuAccessTreeItem(235, 'Assign Items'),
                menuAccessTreeItem(236, 'View Assign Items'),
            ]),
        ],
        'Transfer Item Dispatch to Store' => [
            menuAccessTreeGroup('Transfer Item Dispatch to Store', [
                menuAccessTreeItem(237, 'Transfer to Store'),
                menuAccessTreeItem(238, 'View Dispatch to Store Transfers'),
                menuAccessTreeItem(239, 'Stock Location Report'),
            ]),
        ],
        'Transfer Item Store to Store' => [
            menuAccessTreeGroup('Transfer Item Store to Store', [
                menuAccessTreeItem(240, 'Store to Store Transfer'),
                menuAccessTreeItem(241, 'View Store to Store Transfers'),
                menuAccessTreeItem(242, 'Stock Location Report'),
            ]),
        ],
        'Delivery / Sell' => [
            menuAccessTreeGroup('Delivery / Sell', [
                menuAccessTreeItem(243, 'Add Sell'),
                menuAccessTreeItem(244, 'View Sells'),
            ]),
        ],
        'Assign Challan to Dispatcher' => [
            menuAccessTreeItem(82, 'Assign Challan For Dispatching To Contractor'),
        ],
        'Assign Site To Installation' => [
            menuAccessTreeItem(83, 'Assign Site For Installation To Contractor'),
        ],
        'Installation Project' => [
            menuAccessTreeItem(68, 'Pump Installation'),
        ],
        'Assign Site To Inspection' => [
            menuAccessTreeItem(84, 'Assign Site For Inspection To Contractor'),
        ],
        'Service Complaint' => [
            menuAccessTreeItem(164, 'Service Dashboard'),
            menuAccessTreeItem(137, 'Service Beneficiary List'),
            menuAccessTreeItem(135, 'Allocate Complaints To Engineer'),
            menuAccessTreeItem(136, 'Allocate Not Solved Complaints To Engineer'),
            menuAccessTreeGroup('Service Complaint', [
                menuAccessTreeItem(245, 'Add Service Complaint'),
                menuAccessTreeItem(246, 'View Service Complaint'),
                menuAccessTreeItem(247, 'Service Abstract'),
            ]),
        ],
        'Insurance Site' => [
            menuAccessTreeItem(168, 'Insurance Dashboard'),
            menuAccessTreeItem(121, 'Insurance Site (legacy dashboard)'),
            menuAccessTreeItem(169, 'Pending Insurance'),
            menuAccessTreeItem(170, 'Completed Insurance'),
            menuAccessTreeItem(171, 'Upcoming Renewal Insurance'),
            menuAccessTreeItem(172, 'Expired Insurance'),
            menuAccessTreeItem(173, 'Renewed Insurance'),
        ],
        'Trip Details' => [
            menuAccessTreeItem(138, 'Running Trips'),
            menuAccessTreeItem(139, 'Completed Trips'),
        ],
        'File submission reminder' => [
            menuAccessTreeItem(254, 'File submission reminder'),
        ],
        'Approve Attendance' => [
            menuAccessTreeItem(144, 'Approve Attendance'),
        ],
        'Task Management' => [
            menuAccessTreeItem(253, 'Task Dashboard'),
            menuAccessTreeItem(252, 'Project Head / Department'),
            menuAccessTreeItem(249, 'Create Task'),
            menuAccessTreeItem(250, 'View Tasks'),
            menuAccessTreeItem(251, 'To Do Tasks'),
        ],
        'Reports' => [
            menuAccessTreeItem(142, 'Contractor Billing Report'),
            menuAccessTreeItem(160, 'Download Dispatch Customer CSV'),
            menuAccessTreeItem(29, 'Delivery Challan Report'),
            menuAccessTreeItem(120, 'Material Dispatch Report'),
            menuAccessTreeItem(115, 'Trip Report'),
            menuAccessTreeItem(30, 'Stock Report'),
            menuAccessTreeItem(31, 'Outstanding Stock Report'),
            menuAccessTreeItem(38, 'Customer Report'),
            menuAccessTreeItem(39, 'Daily Record Report'),
            menuAccessTreeItem(99, 'Attendance Report'),
            menuAccessTreeItem(185, 'Attendance Report 2'),
            menuAccessTreeItem(100, 'Vehicle Report'),
            menuAccessTreeItem(65, 'Dealer Report'),
            menuAccessTreeItem(101, 'Store Stock Report'),
            menuAccessTreeItem(184, 'Store Stock Report 2'),
            menuAccessTreeItem(183, 'Serial Location Report'),
            menuAccessTreeItem(102, 'Store Incharge Stock Report'),
            menuAccessTreeItem(103, 'Dispatch Officer Stock Report'),
            menuAccessTreeItem(104, 'Field Survey Report'),
            menuAccessTreeItem(105, 'Dispatch Report'),
            menuAccessTreeItem(106, 'Installation Report'),
            menuAccessTreeItem(107, 'Inspection Report'),
            menuAccessTreeItem(108, 'Site Engineer Report'),
            menuAccessTreeItem(109, 'Dispatch Calling Report'),
            menuAccessTreeItem(110, 'Before Installation Calling Report'),
            menuAccessTreeItem(111, 'After Installation Calling Report'),
            menuAccessTreeItem(112, 'Before Inspection Calling Report'),
            menuAccessTreeItem(119, 'Beneficiary Selection Calling Report'),
            menuAccessTreeItem(143, 'Delay Calculation Report'),
            menuAccessTreeItem(186, 'Delay Calculation Report 2'),
            menuAccessTreeItem(187, 'Employee Tracking Dashboard'),
            menuAccessTreeItem(248, 'Employee Tracking Report'),
        ],
        'Installation Workflow' => [
            menuAccessTreeItem(174, 'Installation Dashboard'),
            menuAccessTreeItem(175, 'Assign Coordinator'),
            menuAccessTreeItem(176, 'Coordinator Action'),
            menuAccessTreeItem(177, 'Manager Action'),
            menuAccessTreeItem(178, 'General Manager Action'),
            menuAccessTreeItem(179, 'GM Extension Requests'),
            menuAccessTreeItem(180, 'Business Head Action'),
            menuAccessTreeItem(181, 'BH Extension Requests'),
            menuAccessTreeItem(182, 'Dispute Sites'),
        ],
        'Warranty' => [
            menuAccessTreeItem(61, 'Warranty'),
        ],
    ];
}

function menuAccessDetailedTreeFlatIds()
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $cache = [];
    foreach (getMenuAccessDetailedTree() as $module => $entries) {
        $cache[$module] = menuAccessDetailedTreeCollectIds($entries);
    }

    return $cache;
}

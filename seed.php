<?php
require_once 'includes/db.php'; // apna DB connection

function randName() {
    $names = ['Aman','Rohit','Priya','Neha','Vikas','Pooja','Rahul','Sneha','Arjun','Kiran'];
    return $names[array_rand($names)];
}

function randStatus($arr) {
    return $arr[array_rand($arr)];
}

echo "Seeding started...\n";

// ================= STUDENTS =================
function randFirstName() {
    $names = ['Amit','Rahul','Priya','Sneha','Ankit','Pooja','Vikram','Neha','Kiran','Arjun','Sonal','Ravi','Deepak','Meena','Rajesh'];
    return $names[array_rand($names)];
}

function randLastName() {
    $names = ['Sharma','Verma','Reddy','Iyer','Nair','Yadav','Patel','Das','Gupta','Singh','Mishra','Chopra'];
    return $names[array_rand($names)];
}


// ================= STUDENTS =================
for ($i = 1; $i <= 200; $i++) {

    $id = 'STU-' . str_pad($i+100, 3, '0', STR_PAD_LEFT);

    $fname = randFirstName();
    $lname = randLastName();

    $email = strtolower($fname.$i.'@gmail.com');
    $phone = '9' . rand(100000000, 999999999);

    $fee = rand(800, 1500);

    // 🎯 Controlled distribution (recommended)
    $rand = rand(1,100);

    if ($rand <= 20) {
        $paid = $fee;
        $status = 'paid';
    } elseif ($rand <= 80) {
        $paid = 0;
        $status = 'pending';
    } else {
        $paid = rand(100, $fee-100);
        $status = 'partial';
    }

    $conn->query("INSERT IGNORE INTO students 
    (id, fname, lname, phone, email, base_fee, net_fee, paid_amt, fee_status, join_date)
    VALUES 
    ('$id','$fname','$lname','$phone','$email',$fee,$fee,$paid,'$status',CURDATE())");
}

// ================= BOOKS =================
for ($i = 1; $i <= 100; $i++) {
    $id = 'BK-' . str_pad($i+100, 3, '0', STR_PAD_LEFT);
    $copies = rand(1, 10);

    $conn->query("INSERT IGNORE INTO books 
    (id, title, author, category, copies, available)
    VALUES 
    ('$id','Book $i','Author $i','Academic',$copies,$copies)");
}

// ================= TRANSACTIONS =================
for ($i = 1; $i <= 200; $i++) {
    $id = 'TRX-' . str_pad($i+100, 3, '0', STR_PAD_LEFT);
    $stu = 'STU-' . str_pad(rand(101,300), 3, '0', STR_PAD_LEFT);
    $book = 'BK-' . str_pad(rand(101,200), 3, '0', STR_PAD_LEFT);

    $statusArr = ['issued','returned','overdue'];
    $status = randStatus($statusArr);

    $issue = date('Y-m-d', strtotime("-".rand(1,10)." days"));
    $due = date('Y-m-d', strtotime($issue . " +7 days"));
    $return = ($status == 'returned') ? date('Y-m-d') : NULL;
    $fine = ($status == 'overdue') ? rand(10,100) : 0;

    $conn->query("INSERT IGNORE INTO transactions 
    (id, student_id, book_id, issue_date, due_date, return_date, fine, status)
    VALUES 
    ('$id','$stu','$book','$issue','$due',".($return ? "'$return'" : "NULL").",$fine,'$status')");
}

// ================= ATTENDANCE =================
for ($i = 1; $i <= 200; $i++) {
    $stu = 'STU-' . str_pad(rand(101,300), 3, '0', STR_PAD_LEFT);
    $statusArr = ['present','absent','late','half'];
    $status = randStatus($statusArr);

    $conn->query("INSERT IGNORE INTO student_attendance 
    (student_id, date, status)
    VALUES 
    ('$stu', CURDATE(), '$status')");
}

// ================= INVOICES =================
for ($i = 1; $i <= 150; $i++) {
    $id = 'INV-' . str_pad($i+100, 3, '0', STR_PAD_LEFT);
    $stu = 'STU-' . str_pad(rand(101,300), 3, '0', STR_PAD_LEFT);

    $amount = rand(800,1500);

    // 🎯 Controlled distribution
    $rand = rand(1,100);

    if ($rand <= 20) {
        // 20% Paid
        $paid = $amount;
        $balance = 0;
        $status = 'paid';
    } elseif ($rand <= 80) {
        // 60% Pending
        $paid = 0;
        $balance = $amount;
        $status = 'pending';
    } else {
        // 20% Partial
        $paid = rand(100, $amount - 100);
        $balance = $amount - $paid;
        $status = 'partial';
    }

    $conn->query("INSERT IGNORE INTO invoices 
    (id, student_id, amount, net_fee, paid_amt, balance, invoice_date, month, status)
    VALUES 
    ('$id','$stu',$amount,$amount,$paid,$balance,CURDATE(),'April','$status')");
}

// ================= EXPENSES =================
for ($i = 1; $i <= 50; $i++) {
    $id = 'EXP-' . str_pad($i+100, 3, '0', STR_PAD_LEFT);
    $amount = rand(500,5000);

    $conn->query("INSERT IGNORE INTO expenses 
    (id, name, amount, category, expense_date)
    VALUES 
    ('$id','Expense $i',$amount,'Other',CURDATE())");
}

echo "✅ Seeding completed!";
?>
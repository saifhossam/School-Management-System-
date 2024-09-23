<?php 
session_start();
if (isset($_SESSION['teacher_id']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Teacher') {
    	

if (isset($_POST['score-1']) &&
    isset($_POST['score-2']) &&
    isset($_POST['score-3']) &&
    isset($_POST['aoutof-1']) &&
    isset($_POST['aoutof-2']) &&
    isset($_POST['aoutof-3']) &&
    isset($_POST['student_id']) &&
    isset($_POST['subject_id']) &&
    isset($_POST['current_year']) &&
    isset($_POST['current_semester'])
    ) {
    
    include '../../DB_connection.php';


    $score_1 = $_POST['score-1'];
    $aoutof_1 = $_POST['aoutof-1'];

    $score_2 = $_POST['score-2'];
    $aoutof_2 = $_POST['aoutof-2'];

    $score_3 = $_POST['score-3'];
    $aoutof_3 = $_POST['aoutof-3'];


    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $current_year = $_POST['current_year'];
    $current_semester = $_POST['current_semester'];
    $teacher_id = $_SESSION['teacher_id'];

    if(empty($score_1) || empty($score_2) || empty($score_3) || empty($aoutof_1) || empty($aoutof_2) || empty($aoutof_3) || empty($student_id) || empty($subject_id) || empty($current_year) || empty($current_semester)){

       $em  = "All fields are required";
        header("Location: ../student-grade.php?student_id=$student_id&error=$em");
        exit;

    }else {
        $data = '';
        $limit = 0;
        if ($score_1 <= 100 && $aoutof_1 <=100 && $score_1 > 0 && $aoutof_1 > 0 && $score_1 <=  $aoutof_1)  {
            $data .= $score_1." ".$aoutof_1; 
             $limit += $aoutof_1;
        } 
        if($score_2 <= 100 && $aoutof_2 <=100 && $score_2 > 0 && $aoutof_2 > 0 && $score_2 <=  $aoutof_2){
           $data .= ",".$score_2." ".$aoutof_2;
           $limit += $aoutof_2;
        }
        if($score_3 <= 100 && $aoutof_3 <=100 && $score_3 > 0 && $aoutof_3 > 0 && $score_3 <=  $aoutof_3){
           $data .= ",".$score_3." ".$aoutof_3;
           $limit += $aoutof_3;
        } 
        if (empty($data)) {
            $em  = "An error occurred";
            header("Location: ../student-grade.php?student_id=$student_id&error=$em");
        exit;
        }else if($limit > 100){
            $em  = "Out of boundaries";
            header("Location: ../student-grade.php?student_id=$student_id&error=$em");
        }
        else {
        if (isset($_POST['student_score_id'])) {
        $sql = "UPDATE student_score SET
                results = ?
                WHERE  semester=?
                AND year=? AND student_id=? AND teacher_id=? AND subject_id=?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$data, $current_semester, $current_year, $student_id, $teacher_id, $subject_id]);
        $sm = "The Score has been updated successfully!";
        header("Location: ../student-grade.php?student_id=$student_id&success=$sm");
        exit;
          }else {
             $sql = "INSERT INTO student_score(semester, year, student_id, teacher_id, subject_id, results)VALUES(?,?,?,?,?,?)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$current_semester, $current_year, $student_id, $teacher_id, $subject_id, $data]);
        $sm = "The Score has been created successfully!";
        header("Location: ../student-grade.php?student_id=$student_id&success=$sm");
          }
        }

    }
    
  }else {
  	$em = "An error occurred";
    header("Location: ../classes.php?error=$em");
    exit;
  }

  }else {
    header("Location: ../../logout.php");
    exit;
  } 
}else {
	header("Location: ../../logout.php");
	exit;
} 

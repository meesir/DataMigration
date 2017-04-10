<?php
$list = array();
$userName = "username";
$password = "password";

try{
        //Connect to Temp Database
        $dbh = new PDO('mysql:host=localhost;dbname=appointment', $userName, $password);

            
            //Add all records to array
            foreach ($dbh->query('select * from app') as $row)
                {
                     array_push($list, $row);
                }   
                
                
    
    
    } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
    }
    
 //Variables to hold records for Insertion into new Database
$type = array();
$date = array();
$distance = array();
     
     
    foreach ($list as $line)
    {
        array_push($type ,$line[0] );
        array_push($date ,$line[1] );
        array_push($distance,$line[2]);     
    }
    
    
    //Function to reformat dates for MySQL Date datatype
   function fixDates($array)
   {
       $newDates = array();
       $day;
       $month;
       $year;
       foreach ($array as $date)
       {
           
           
           $month = substr($date, 0, 2);
           $day = substr($date, 3, 2);
           $year = substr($date, 6);
           array_push($newDates, $year . '-' . $month . '-' . $day);
           
           
       }
              
       return $newDates;
   }
   
//Array of properly formatted dates   
$cleanDates = fixDates($date);

try{
    
        $dbh = new PDO('mysql:host=localhost;dbname=insurance', $userName, $password);

            //Counter is used to syc the distance and type fields with the dates 
            $counter = 0;
            foreach ($cleanDates as $date)
                {
                     $sql = $dbh->prepare("INSERT INTO appointment (date, kms, type) VALUES (:date, :kms, :type)");
                     $sql->bindParam(':date', $date);
                     $sql->bindParam(':kms', $distance[$counter]);
                     $sql->bindParam(':type', $type[$counter]);
                     $sql->execute();
                     $counter++;
                     
                }   
                   
    
    } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
    }

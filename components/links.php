<?php
$links = array(
  array(
    "title" => "Dashboard",
    "url" => "$SERVER_NAME/views/admin/",
    "allowedViews" => array("admin"),
    "config" => array(
      "icon" => "mdi mdi-file-document-box"
    )
  ),
  array(
    "title" => "Announcements",
    "url" => "$SERVER_NAME/views/admin/announcements",
    "allowedViews" => array("admin"),
    "config" => array(
      "icon" => "mdi mdi-bullhorn"
    )
  ),
  array(
    "title" => "Students",
    "url" => "$SERVER_NAME/views/admin/students",
    "allowedViews" => array("admin"),
    "config" => array(
      "icon" => "mdi mdi-account-multiple"
    )
  ),
  array(
    "title" => "Teachers",
    "url" => "$SERVER_NAME/views/admin/teachers",
    "allowedViews" => array("admin"),
    "config" => array(
      "icon" => "mdi mdi-account-multiple"
    )
  ),
  array(
    "title" => "Admins",
    "url" => "$SERVER_NAME/views/admin/admins",
    "allowedViews" => array("admin"),
    "config" => array(
      "icon" => "mdi mdi-account-multiple"
    )
  ),
  array(
    "title" => "Course",
    "url" => "$SERVER_NAME/views/admin/courses",
    "allowedViews" => array("admin"),
    "config" => array(
      "icon" => "mdi mdi-clipboard-text"
    )
  ),
  array(
    "title" => "Alumnus",
    "url" => "$SERVER_NAME/views/admin/alumnus",
    "allowedViews" => array("admin"),
    "config" => array(
      "icon" => "mdi mdi-city"
    )
  ),
  array(
    "title" => "Activity Log",
    "url" => "$SERVER_NAME/views/admin/activities",
    "allowedViews" => array("admin"),
    "config" => array(
      "icon" => "mdi mdi-history"
    )
  ),
  array(
    "title" => "Announcements",
    "url" => "$SERVER_NAME/views/user/announcements",
    "allowedViews" => array("student", "teacher", "alumni"),
    "config" => array(
      "icon" => "mdi mdi-bullhorn"
    )
  ),
  array(
    "title" => "About",
    "url" => "$SERVER_NAME/views/user/about",
    "allowedViews" => array("student", "teacher", "alumni"),
    "config" => array(
      "icon" => "mdi mdi-information"
    )
  ),
);
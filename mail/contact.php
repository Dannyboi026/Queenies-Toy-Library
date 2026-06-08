<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location:../pages/contact.html');exit;}

// ── Fill these two lines ──────────────────────────────
$ws_email = 'info@queeniestoylibrary.org';
$ws_pass  = 'xxxx xxxx xxxx xxxx'; // Google Workspace App Password
// ─────────────────────────────────────────────────────

$name=$_POST['name']??''; $email=filter_var($_POST['email']??'',FILTER_VALIDATE_EMAIL);
$subj=$_POST['subject']??''; $phone=$_POST['phone']??''; $msg=$_POST['message']??'';
$name=htmlspecialchars(trim($name)); $phone=htmlspecialchars(trim($phone));
$subj=htmlspecialchars(trim($subj)); $msg=htmlspecialchars(trim($msg));
if(!$name||!$email||!$msg){header('Location:../pages/contact.html?status=error');exit;}

function gm($e,$p){$m=new PHPMailer(true);$m->isSMTP();$m->Host='smtp.gmail.com';$m->SMTPAuth=true;$m->Username=$e;$m->Password=$p;$m->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;$m->Port=587;return $m;}

try{
  $m=gm($ws_email,$ws_pass);
  $m->setFrom($ws_email,"Queenie's Toy Library");$m->addAddress($ws_email);$m->addReplyTo($email,$name);
  $m->isHTML(true);$m->Subject="Contact: $subj — $name";
  $m->Body="<div style='font-family:Arial,sans-serif;max-width:580px;background:#fff;padding:32px;border-radius:12px;'><div style='background:#1A0A2E;padding:20px 24px;border-radius:8px;margin-bottom:24px;'><h2 style='color:#fff;margin:0;font-size:20px;'>New Contact — Queenie's Toy Library</h2></div><table style='width:100%;font-size:15px;border-collapse:collapse;'><tr><td style='padding:10px 0;color:#6B5E7A;font-weight:700;width:100px;border-bottom:1px solid #f0e8ff;'>Name</td><td style='padding:10px 0;color:#1A0A2E;font-weight:700;border-bottom:1px solid #f0e8ff;'>$name</td></tr><tr><td style='padding:10px 0;color:#6B5E7A;font-weight:700;border-bottom:1px solid #f0e8ff;'>Email</td><td style='padding:10px 0;border-bottom:1px solid #f0e8ff;'><a href='mailto:$email'>$email</a></td></tr><tr><td style='padding:10px 0;color:#6B5E7A;font-weight:700;border-bottom:1px solid #f0e8ff;'>Phone</td><td style='padding:10px 0;border-bottom:1px solid #f0e8ff;'>$phone</td></tr><tr><td style='padding:10px 0;color:#6B5E7A;font-weight:700;border-bottom:1px solid #f0e8ff;'>Subject</td><td style='padding:10px 0;border-bottom:1px solid #f0e8ff;'>$subj</td></tr><tr><td style='padding:10px 0;color:#6B5E7A;font-weight:700;vertical-align:top;'>Message</td><td style='padding:10px 0;line-height:1.7;color:#1A0A2E;'>$msg</td></tr></table></div>";
  $m->send();
  $r=gm($ws_email,$ws_pass);$r->setFrom($ws_email,"Queenie's Toy Library");$r->addAddress($email,$name);$r->isHTML(true);
  $r->Subject="We received your message — Queenie's Toy Library";
  $r->Body="<div style='font-family:Arial,sans-serif;max-width:580px;'><div style='background:#1A0A2E;padding:28px;border-radius:12px 12px 0 0;text-align:center;'><h2 style='color:#fff;margin:0;'>Thank You, $name!</h2><p style='color:#F59E0B;margin:8px 0 0;font-size:16px;'>We've received your message.</p></div><div style='background:#fff;padding:32px;border:1px solid #f0e8ff;border-radius:0 0 12px 12px;'><p style='color:#6B5E7A;font-size:16px;line-height:1.8;'>We'll get back to you within 1–2 business days. In the meantime, why not browse our free toy collection?</p><div style='text-align:center;margin:28px 0;'><a href='https://queeniestoylibrary.org/pages/borrow.html' style='background:#FF6B6B;color:#fff;padding:14px 32px;border-radius:50px;text-decoration:none;font-weight:700;font-size:15px;'>Browse &amp; Borrow Free Toys</a></div><p style='color:#6B5E7A;font-size:14px;margin:0;'>— The Queenie's Toy Library Team</p></div></div>";
  $r->send();
  header('Location:../pages/contact.html?status=success');
}catch(Exception $e){header('Location:../pages/contact.html?status=error');}

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location:../pages/donate.html');exit;}

// ── Fill these two lines ──────────────────────────────
$ws_email = 'info@queeniestoylibrary.org';
$ws_pass  = 'xxxx xxxx xxxx xxxx'; // Google Workspace App Password
// ─────────────────────────────────────────────────────

$name=htmlspecialchars(trim($_POST['name']??''));
$email=filter_var(trim($_POST['email']??''),FILTER_VALIDATE_EMAIL);
$phone=htmlspecialchars(trim($_POST['phone']??''));
$desc=htmlspecialchars(trim($_POST['toy_description']??''));
$count=htmlspecialchars(trim($_POST['toy_count']??''));
$cond=htmlspecialchars(trim($_POST['condition']??''));
$avail=htmlspecialchars(trim($_POST['availability']??''));
if(!$name||!$email||!$desc){header('Location:../pages/donate.html?status=error');exit;}

try{
  $m=new PHPMailer(true);$m->isSMTP();$m->Host='smtp.gmail.com';$m->SMTPAuth=true;
  $m->Username=$ws_email;$m->Password=$ws_pass;$m->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;$m->Port=587;
  $m->setFrom($ws_email,"Queenie's Toy Library");$m->addAddress($ws_email);$m->addReplyTo($email,$name);
  $m->isHTML(true);$m->Subject="Toy Donation from $name";
  $m->Body="<div style='font-family:Arial,sans-serif;max-width:580px;background:#fff;padding:32px;border-radius:12px;'><div style='background:#1A0A2E;padding:20px 24px;border-radius:8px;margin-bottom:24px;'><h2 style='color:#fff;margin:0;font-size:20px;'>New Toy Donation — Queenie's Toy Library</h2></div><table style='width:100%;font-size:15px;border-collapse:collapse;'><tr><td style='padding:10px 0;color:#6B5E7A;font-weight:700;width:130px;border-bottom:1px solid #f0e8ff;'>Name</td><td style='padding:10px 0;color:#1A0A2E;font-weight:700;border-bottom:1px solid #f0e8ff;'>$name</td></tr><tr><td style='padding:10px 0;color:#6B5E7A;font-weight:700;border-bottom:1px solid #f0e8ff;'>Email</td><td style='padding:10px 0;border-bottom:1px solid #f0e8ff;'><a href='mailto:$email'>$email</a></td></tr><tr><td style='padding:10px 0;color:#6B5E7A;font-weight:700;border-bottom:1px solid #f0e8ff;'>Phone</td><td style='padding:10px 0;border-bottom:1px solid #f0e8ff;'>$phone</td></tr><tr><td style='padding:10px 0;color:#6B5E7A;font-weight:700;border-bottom:1px solid #f0e8ff;'>Qty</td><td style='padding:10px 0;border-bottom:1px solid #f0e8ff;'>$count</td></tr><tr><td style='padding:10px 0;color:#6B5E7A;font-weight:700;border-bottom:1px solid #f0e8ff;'>Condition</td><td style='padding:10px 0;border-bottom:1px solid #f0e8ff;'>$cond</td></tr><tr><td style='padding:10px 0;color:#6B5E7A;font-weight:700;border-bottom:1px solid #f0e8ff;vertical-align:top;'>Description</td><td style='padding:10px 0;border-bottom:1px solid #f0e8ff;line-height:1.7;'>$desc</td></tr><tr><td style='padding:10px 0;color:#6B5E7A;font-weight:700;'>Availability</td><td style='padding:10px 0;'>$avail</td></tr></table></div>";
  $m->send();
  $r=new PHPMailer(true);$r->isSMTP();$r->Host='smtp.gmail.com';$r->SMTPAuth=true;
  $r->Username=$ws_email;$r->Password=$ws_pass;$r->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;$r->Port=587;
  $r->setFrom($ws_email,"Queenie's Toy Library");$r->addAddress($email,$name);$r->isHTML(true);
  $r->Subject="Thank you for your donation — Queenie's Toy Library";
  $r->Body="<div style='font-family:Arial,sans-serif;max-width:580px;'><div style='background:#1A0A2E;padding:28px;border-radius:12px 12px 0 0;text-align:center;'><h2 style='color:#fff;margin:0;'>Thank You, $name! ❤️</h2><p style='color:#F59E0B;margin:10px 0 0;font-size:16px;'>Your toy donation means the world to us.</p></div><div style='background:#fff;padding:32px;border:1px solid #f0e8ff;border-radius:0 0 12px 12px;'><p style='color:#6B5E7A;font-size:16px;line-height:1.8;'>We'll be in touch shortly to arrange a convenient drop-off time. Every toy you give goes directly to a child who needs it.</p><p style='color:#6B5E7A;font-size:14px;margin:24px 0 0;'>— The Queenie's Toy Library Team</p></div></div>";
  $r->send();
  header('Location:../pages/donate.html?status=success');
}catch(Exception $e){header('Location:../pages/donate.html?status=error');}

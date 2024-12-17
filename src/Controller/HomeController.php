<?php

namespace App\Controller;

use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;

class HomeController extends AbstractController
{
    public function index(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? null;
            $email = $_POST['email'] ?? null;
            $message = $_POST['message'] ?? null;

            $formErrors = [];

            if (empty($name)) {
                $this->addFlash('danger', 'Field name is required');
                $formErrors['name'] = 'Field name is required';
            }

            if (empty($email)) {
                $this->addFlash('danger', 'Field email is required');
                $formErrors['email'] = 'Field email is required';
            }

            if (empty($message)) {
                $this->addFlash('danger', 'Field message is required');
                $formErrors['message'] = 'Field message is required';
            }

            if (!empty($formErrors)) {
                return $this->render('pages/home/index.html.twig', [
                    'form_errors' => $formErrors,
                ]);
            }

            // Send email
            $emailParams = (new EmailParams())
                ->setFrom($_ENV['MAILERSEND_MAIL_ADDRESS'])
                ->setFromName('Xavier Lauer')
                ->setRecipients([new Recipient($email, $name)])
                ->setSubject('Contact form')
                ->setHtml($message)
            ;

            (new MailerSend(['api_key' => $_ENV['MAILERSEND_API_KEY']]))->email->send($emailParams);

            $this->addFlash('success', 'Your message has been sent. I will contact you soon.');
        }

        return $this->render('pages/home/index.html.twig');
    }
}

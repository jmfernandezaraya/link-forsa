<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\SuperAdmin\EmailTemplate;

class RegisterOTPMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $data;

    private $sender_name, $sender_email;
    public $subject, $contents;
    
    public function __construct($data, $locale)
    {
        $this->data = $data;

        $this->sender_name = env('MAIL_FROM_NAME');
        $this->sender_email = env('MAIL_FROM_ADDRESS');
        $this->subject = __('Frontend.verify_email');

        $contents_html = '';
        $email_template = EmailTemplate::where('template', 'verify_email')->first();
        if ($email_template) {
            if ($email_template->sender_email) {
                $this->sender_email = $email_template->sender_email;
            } else if ($site_setting_value['smtp']['default_sender_email']) {
                $this->sender_email = $site_setting_value['smtp']['default_sender_email'];
            }
            if ($locale == 'en') {
                if ($email_template->sender_name) {
                    $this->sender_name = $email_template->sender_name;
                }
                if ($email_template->subject) {
                    $this->subject = $email_template->subject;
                }
                if ($email_template->content) {
                    $contents_html = $email_template->content;
                }
            } else {
                if ($email_template->sender_name_ar) {
                    $this->sender_name = $email_template->sender_name_ar;
                }
                if ($email_template->subject_ar) {
                    $this->subject = $email_template->subject_ar;
                }
                if ($email_template->content_ar) {
                    $contents_html = $email_template->content_ar;
                }
            }

            $user_name = '';
            if ($locale == 'en') {
                $user_name = $this->data->user->first_name_en . ' ' . $this->data->user->last_name_en;
            } else {
                $user_name = $this->data->user->first_name_ar . ' ' . $this->data->user->last_name_ar;
            }
            foreach ($email_template->keywords as $email_template_keyword) {
                if ($email_template_keyword == 'verify_url') {
                    $contents_html = str_replace('[verify_url]', route('verify-email-user', $this->data->user->remember_token), $contents_html);
                } else if ($email_template_keyword == 'user_name') {
                    $contents_html = str_replace('[user_name]', $user_name, $contents_html);
                    $this->sender_name = str_replace('[user_name]', $user_name, $this->sender_name);
                    $this->subject = str_replace('[user_name]', $user_name, $this->subject);
                } else {
                    if (isset($this->user->{$email_template_keyword})) {
                        $contents_html = str_replace('[' . $email_template_keyword . ']', $this->data->{$email_template_keyword}, $contents_html);
                    }
                }
            }
            $contents_html = str_replace('[website_link]', url('/'), $contents_html);
            $contents_html = str_replace('[app_name]', config('app.name'), $contents_html);
            $this->sender_name = str_replace('[website_link]', url('/'), $this->sender_name);
            $this->sender_name = str_replace('[app_name]', config('app.name'), $this->sender_name);
            $this->subject = str_replace('[website_link]', url('/'), $this->subject);
            $this->subject = str_replace('[app_name]', config('app.name'), $this->subject);
        }

        $this->contents = [];
        $contents = explode("\n\n", $contents_html);
        foreach ($contents as $content) {
            $this->contents[] = [
                'type' => 'message',
                'message' => $content,
            ];
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->markdown('mail.template', ['contents' => $this->contents]);
        if ($this->sender_email) {
            if ($this->sender_name) {
                $mail = $mail->from($this->sender_email, $this->sender_name);
            } else {
                $mail = $mail->from($this->sender_email);
            }
        }
        if ($this->subject) {
            $mail = $mail->subject($this->subject);
        }
        return $mail;
    }
}
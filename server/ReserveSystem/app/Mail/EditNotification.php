<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EditNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $title;
    protected $date;
    protected $start;
    protected $join_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$date,$start,$join_url)
    {
        $this->title = sprintf('%s様の予約内容を変更しました。',$name);
        $this->date = $date;
        $this->start = $start;
        $this->join_url = $join_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->text('mail/edit_mail')
                    ->subject($this->title)
                    ->with([
                        'date' => $this->date,
                        'start' => $this->start,
                        'join_url' => $this->join_url,
                    ]);
    }
}

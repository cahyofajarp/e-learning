<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkNotification extends Notification
{
    use Queueable;

    public $lesson;
    public $work;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($lesson,$work)
    {
        $this->lesson = $lesson;
        $this->work = $work;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        return [
           'type_notif' => 'work',
           'message' => auth()->user()->teacher->name.' Baru saja membuat <b>Tugas</b>!',
           'lesson' => $this->lesson,
           'user' => auth()->user(),
           'data_notif' => [
               'id' => $this->work->id,
               'title' => $this->work->title,
               'created_at' => $this->work->created_at
           ],
       ];

    } 
    
    public function toBroadcast($notifiable)
    {
      return  new BroadcastMessage([
        'type_notif' => 'work',
        'message' => auth()->user()->teacher->name.' Baru saja membuat <b>Tugas</b>!',
        'lesson' => $this->lesson,
        'user' => auth()->user(),
        'data_notif' => [
            'id' => $this->work->id,
            'title' => $this->work->title,
            'created_at' => $this->work->created_at
        ],

       ]);

   }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [

        ];
    }
}

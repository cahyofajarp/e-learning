<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;
    public $lesson;
    public $test;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($lesson,$test)
    {
        $this->lesson = $lesson;
        $this->test = $test;
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
            'type_notif' => 'test',
            'message' => auth()->user()->teacher->name.' Baru saja membuat <b>Test</b>! <b>Ayo Kerjakan Sekarang!</b>',
            'lesson' => $this->lesson,
            'user' => auth()->user(), 
            'data_notif' => [
                'id' => $this->test->id,
               'title' => $this->test->name,
               'created_at' => $this->test->created_at
           ],
       ];

    } 
    
    public function toBroadcast($notifiable)
    {
      return  new BroadcastMessage([
        'type_notif' => 'test',
        'message' => auth()->user()->teacher->name.' Baru saja membuat <b>Test</b>! <b>Ayo Kerjakan Sekarang!</b>',
        'lesson' => $this->lesson,
        'user' => auth()->user(), 
        'data_notif' => [
                'id' => $this->test->id,
               'title' => $this->test->name,
               'created_at' => $this->test->created_at
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
            //
        ];
    }
}

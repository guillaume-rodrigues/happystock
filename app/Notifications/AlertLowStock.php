<?php

namespace App\Notifications;

use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AlertLowStock extends Notification
{
    use Queueable;

    /** @var Product|null */
    private $objCurrentProduct = null;

    /**
     * Create a new notification instance.
     *
     * @param Product $objProduct
     * @return void
     */
    public function __construct($objProduct)
    {
        $this->objCurrentProduct = $objProduct;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $strUrl = url('/api/products/'.$this->objCurrentProduct->id);

        return (new MailMessage)
            ->subject('Low quantity in stock of product '.$this->objCurrentProduct->name)
            ->greeting('Hello!')
            ->line("The quantity in stock of the product {$this->objCurrentProduct->name} ".
                '(current stock: '.
                $this->objCurrentProduct->quantity.
                ') is too low (under '.
                $this->objCurrentProduct::QUANTITY_LIMIT.
                ')'
            )
            ->action('Check product data', $strUrl)
            ->line('Thank you for using our application!');
    }
}

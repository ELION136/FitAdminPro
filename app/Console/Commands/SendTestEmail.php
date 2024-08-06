<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-test-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        Mail::raw('This is a test email.', function ($message) {
            $message->to('fita29884@gmail.com')
                    ->subject('Test Email');
        });

        $this->info('Test email sent successfully!');
    }
}

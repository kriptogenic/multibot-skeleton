<?php

namespace App\Console\Commands;

use App\Telegram\Project;
use Illuminate\Console\Command;

abstract class ProjectChoiceCommand extends Command
{
    protected function getProject(): Project
    {
        $bot_id = $this->argument('bot_id');
        $project = $bot_id === null ? null : Project::tryFrom($bot_id);

        if ($project !== null) {
            return $project;
        }

        $choices = Project::collect()
            ->mapWithKeys(function (Project $project) {
                return ['#' . $project->value => $project->name];
            })
            ->toArray();
        $bot_id = $this->choice('Choose project to set webhook', $choices);

        return Project::from(substr($bot_id, 1));
    }
}

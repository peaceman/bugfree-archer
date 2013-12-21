set :application, 'edm-market'
set :repo_url, 'git@github.com:peaceman/bugfree-archer.git'

SSHKit.config.command_map[:artisan] = '/usr/bin/env php artisan --no-interaction'

# ask :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }

# set :deploy_to, '/var/www/my_app'
# set :scm, :git

# set :format, :pretty
# set :log_level, :debug
# set :pty, true

# set :linked_files, %w{config/database.yml}
# set :linked_dirs, %w{bin log tmp/pids tmp/cache tmp/sockets vendor/bundle public/system}
set :linked_dirs, %w{app/storage/logs app/storage/sessions}

# set :default_env, { path: "/opt/ruby/bin:$PATH" }
# set :keep_releases, 5

namespace :deploy do
  task :restart do
    # noop
  end
  
  desc 'create a human friendly string to use as the release folder name'
  task :new_release_path do
    set_release_path env.timestamp.strftime("%Y-%m-%d_%H-%M-%S")
  end
  
  desc 'executes database migrations through laravel artisan'
  task :migrate_database do
    on roles(:app), in: :parallel do
      with({'APPLICATION_ENV' => fetch(:stage)}) do
        within release_path do
          execute :artisan, :migrate
        end
      end
    end
  end
  
  task :extract_database_credentials do
    # noop
  end

  before :starting, 'composer:install_executable'
  before :updated, 'deploy:extract_database_credentials'
  after :updated, 'deploy:migrate_database'
  after :finishing, 'deploy:cleanup'

end

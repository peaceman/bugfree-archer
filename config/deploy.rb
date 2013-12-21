set :application, 'edm-market'
set :repo_url, 'git@github.com:peaceman/bugfree-archer.git'

SSHKit.config.command_map[:artisan] = '/usr/bin/env php artisan'

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
    on roles(:db), in: :parallel do
      within release_path do
        execute :artisan, :migrate
      end
    end
  end

  before :starting, 'composer:install_executable'
  after :updated, 'deploy:migrate_database'
  after :finishing, 'deploy:cleanup'

end

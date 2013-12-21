set :application, 'edm-market'
set :repo_url, 'git@github.com:peaceman/bugfree-archer.git'

# ask :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }

# set :deploy_to, '/var/www/my_app'
# set :scm, :git

# set :format, :pretty
# set :log_level, :debug
# set :pty, true

# set :linked_files, %w{config/database.yml}
# set :linked_dirs, %w{bin log tmp/pids tmp/cache tmp/sockets vendor/bundle public/system}

# set :default_env, { path: "/opt/ruby/bin:$PATH" }
# set :keep_releases, 5

namespace :deploy do
  task :restart do
    # noop
  end

  before :starting, 'composer:install_executable'
  after :finishing, 'deploy:cleanup'

end

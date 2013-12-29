set :stage, :demo

set :tmp_dir, '/home/edm/tmp'
set :branch, 'master'
set :deploy_to, '/var/www/virtual/edm'
set :database_credentials_path, "app/config/#{fetch(:stage)}/database.php"

set :uberspace_config_source_files, ["/home/edm/aws.env"]
set :uberspace_config_target_files, ["app/config/packages/aws/aws-sdk-php-laravel/#{fetch(:stage)}/config.php"]
set :uberspace_config_placeholders_mapping, {'##AWS_KEY##' => 'AWS_KEY', '##AWS_SECRET##' => 'AWS_SECRET'}
SSHKit.config.command_map[:composer] = "#{shared_path.join("composer.phar")}"

# Simple Role Syntax
# ==================
# Supports bulk-adding hosts to roles, the primary
# server in each group is considered to be the first
# unless any hosts have the primary property set.
# role :app, %w{deploy@example.com}
# role :web, %w{deploy@example.com}
# role :db,  %w{deploy@example.com}

# Extended Server Syntax
# ======================
# This can be used to drop a more detailed server
# definition into the server list. The second argument
# something that quacks like a hash can be used to set
# extended properties on the server.
server 'octans.uberspace.de', user: 'edm', roles: %w{web app db}

namespace :deploy do
  before :updated, 'uberspace:extract_database_credentials'
  before :updated, 'uberspace:replace_placeholders'

  task :restart do
    on roles(:app), in: :parallel do
      execute 'killall -u edm -9 php-cgi; true'
    end
  end
end

# you can set custom ssh options
# it's possible to pass any option but you need to keep in mind that net/ssh understand limited list of options
# you can see them in [net/ssh documentation](http://net-ssh.github.io/net-ssh/classes/Net/SSH.html#method-c-start)
# set it globally
#  set :ssh_options, {
#    keys: %w(/home/rlisowski/.ssh/id_rsa),
#    forward_agent: false,
#    auth_methods: %w(password)
#  }
# and/or per server
# server 'example.com',
#   user: 'user_name',
#   roles: %w{web app},
#   ssh_options: {
#     user: 'user_name', # overrides user setting above
#     keys: %w(/home/user_name/.ssh/id_rsa),
#     forward_agent: false,
#     auth_methods: %w(publickey password)
#     # password: 'please use keys'
#   }
# setting per server overrides global ssh_options

# fetch(:default_env).merge!(rails_env: :demo)

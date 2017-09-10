alias ls='ls -F --color=always'
alias dir='dir -F --color=always'
alias ll='ls -l'
alias cp='cp -iv'
alias rm='rm -i'
alias mv='mv -iv'
alias grep='grep --color=auto -in'
alias ..='cd ..'

WEBROOT="/var/www/app"

# symfony console
alias sc='php $XDCONF bin/console'
alias sccc='sc cache:clear'
alias scdsu='sc doctrine:schema:update'
alias scdfl='sc doctrine:fixtures:load'
alias scrd='sc debug:router'
alias sccd='sc debug:container'
alias scrl='sc doctrine:schema:drop --force --full-database && sc doctrine:schema:update --force && sc doctrine:fixtures:load'

alias scrlmongo='sc doctrine:mongodb:fixtures:load'

# composer
alias cou='composer update'
alias coi='composer install'

# compass
alias cwatch='compass watch --poll' 
alias ccompile='compass compile'

# phpunit
alias pu='phpunit -c app'

# mysql
alias db='mysql --host db -uapp -p123 app'
alias dbdump='mysqldump --host db -uapp -p123 app --no-data > web/dump.mysql'

# staging
alias coinodev='coi --no-dev --optimize-autoloader'
alias pack='tar cjf ~/deploy.tbz -X deploy.exclude *'
alias scppack='scp -i ~/.ssh/my-ssh-key ~/deploy.tbz'
alias sshserver='ssh -i ~/.ssh/my-ssh-key'

# create ssh key
alias newkey='ssh-keygen -t rsa -f ~/.ssh/my-ssh-key -C'
alias showkey='chmod 400 ~/.ssh/my-ssh-key && cat ~/.ssh/my-ssh-key.pub'

# xdebug
alias xd='XDEBUG_CONFIG="remote_host=`echo $SSH_CLIENT |cut -d" " -f1`" PHP_IDE_CONFIG="serverName=${HOSTNAME}"'

# miscellaneous
alias www='cd ${WEBROOT}'
alias please='sudo'
alias NOW='sudo !!'
www

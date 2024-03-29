#!/usr/bin/env bash

SLOP_DIR="/opt/slop_shell/"
export SLOP_DIR
declare -a postgres_versions=('13' '12' '11' '10' '9' '8' '7')
declare -a pg_dir_exists=()
choice=''

function root_check(){
  if [[ $( id | grep -c "uid=0" ) -eq 0 ]]; then
    echo "Please run as root."
    exit
  fi
}

function apt_prep(){
  if ! [ -f "/usr/lib/postgresql/13/bin/pg_ctl" ]; then
    echo "Install pre-requsites. And as such, installing postgresql 13."
    apt install postgresql-13 php php-pear
  fi
}

function non_apt_prep(){
  if [[ $( which pg_ctl | grep -c 'no pg_ctl' ) -gt 0 ]]; then
    case $1 in
      'arch' |  "black")
        pacman -Syy postgresql php php-pear
      ;;
      'red' | 'fedora')
        dnf -y -b --refresh install postgresql-server postgresql php php-pear
    esac
  fi
}

function deb() {
  for ((version = 0; version < ${#postgres_versions[@]}; version++)); do
    deb_pg_dir="/usr/lib/postgresql/${postgres_version[$version]}/bin/pg_ctl"
    if [ -f $deb_pg_dir ]; then
      pg_dir_exists+=("${deb_pg_dir}")
    fi
  done
   echo $( seq -s "-" 50 | sed 's/[0-9]*//g');echo;echo
  echo "These are the confirmed pg_ctl binaries on disk, please choose from them.";echo
  for ((con = 0; con < ${#pg_dir_exists[@]}; con++)); do
    echo "Index: ${con} : ${pg_dir_exists[con]}"
  done
  echo
   echo $( seq -s "-" 50 | sed 's/[0-9]*//g');echo;echo
  echo -n "Which one shall we be using? ->"
  read choice
  echo -n "${pg_dir_exists[$choice]}"
  echo
}


# need to diagnose whats going on with this function and why its not properly detecting if the script is running with the root user or not.
#root_check
if test $# -lt 1; then
  echo Missing args.
  echo "$0" [os] [shell you are using]
  echo "[shell you are using] examples:  bash | zsh | csh | ksh"
  echo "[os] examples: deb | debian | kali | parrot | red (hat) | fedora | arch | black (arch)"
else
  shell_type=$2
  case $1 in
  'debian' | 'deb' | 'kali' | 'parrot')
    apt_prep
    pg_choice=deb
    ;;
  'red' | 'fedora' | 'arch' | 'black')
      non_apt_prep "$1"
      pg_choice="$(which pg_ctl)"
    ;;
  esac
  echo "Attempting to start postgresql"
  if test $PG_SLOP -n; then
    echo "PGSQL Is not started, trying to init the db, and then start it."
    if ! [ -e $SLOP_DIR ]; then
      sudo mkdir -p $SLOP_DIR/slop_data && sudo $(which chown) -R $( whoami ):postgres $SLOP_DIR
    else
      echo "Directory is already created."
    fi
    cd $SLOP_DIR/slop_data && $( which createdb ) sloppy_bots -E utf-8 -O postgres -h localhost -p 5432 -U postgres
    # shellcheck disable=SC2046
    sudo $(which chmod) 776 /var/run/postgresql
    # shellcheck disable=SC2046
    sudo $(which chown) postgres:postgres /var/run/postgresql
    $pg_choice start -D $SLOP_DIR/slop_data -l $SLOP_DIR/main.log
    # shellcheck disable=SC2046
    echo "If any of these commands failed, you will need to re-run them yourself."
    echo "export PG_SLOP=1" >> "$HOME/.${shell_type}rc"
    source "$HOME/.${shell_type}rc"
  else
    echo "PG Appears to be running!!"
  fi
fi

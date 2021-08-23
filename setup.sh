#!/usr/bin/env bash

SLOP_DIR="/opt/slop_shell/"
declare -a postgres_versions=('13' '12' '11' '10' '9' '8' '7')
declare -a pg_dir_exists=()
choice=''

function apt_prep(){
  echo "Coming soon."
}

function aur_prep(){
  echo "Coming soon."
}

function rhel_prep(){
  echo "Coming soon."
}

function deb() {
  for ((version = 0; version < ${#postgres_versions[@]}; version++)); do
    deb_pg_dir="/usr/lib/postgresql/${version}/bin/pg_ctl"
    if [ -f $deb_pg_dir ]; then
      pg_dir_exists+=("${deb_pg_dir}")
    fi
  done
  printf '++%.0s'{1..45};echo;echo
  echo "These are the confirmed pg_ctl binaries on disk, please choose from them.";echo
  for ((con = 0; con < ${#pg_dir_exists[@]}; con++)); do
    echo "Index: ${con} : ${pg_dir_exists[con]}"
  done
  echo
  printf '++%.0s'{1..45};echo;echo
  echo -n "Which one shall we be using? ->"
  read choice
  echo -n "${pg_dir_exists[$choice]}"
  echo
  return "${pg_dir_exists[$choice]}"
}



if test $# -lt 1; then
  echo Missing args.
  echo "$0" [os] [shell you are using]
  echo "[shell you are using] examples:  bash | zsh | csh | ksh"
  echo "[os] examples: deb | debian | kali | parrot | red (hat) | fedora | arch | black (arch)"
else
  shell_type=$2
  case $1 in
  'debian' | 'deb' | 'kali' | 'parrot')
    pg_choice=deb
    ;;
  'red' | 'fedora' | 'arch' | 'black')
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
    $pg_choice initdb -D $SLOP_DIR/slop_data -l $SLOP_DIR/main.log
    cd $SLOP_DIR/slop_data && $(which createdb) sloppy_bots -E utf-8 -O $(whoami) -h localhost -p 5432 -U postgres
    # shellcheck disable=SC2046
    sudo $(which chmod) 770 /var/run/postgresql
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

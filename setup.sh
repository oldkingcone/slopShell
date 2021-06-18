#!/usr/bin/env bash

function startPG() {
    echo "Attempting to start postgresql"
    if test "$PG_SLOP" -ne 1; then
      echo "PGSQL Is not started, trying to init the db, and then start it."
      sudo mkdir -p /opt/postgres/slop && sudo chown -R "$( whoami )":"$( whoami )" /opt/postgres/slop
      SLOP_DIR="/opt/postgres/slop"
      $( which pg_ctl ) init -D /opt/postgres/slop -l /opt/postgres/slop
      $( which pg_ctl ) start -D /opt/postgres/slop -l /opt/postgres/slop
      cd $SLOP_DIR && $( which createdb )  sloppy_bots -E utf-8 -O "$( whoami )" -h localhost -p 5432 -U notroot
      PG_SLOP=1
      export PG_SLOP
    else
      echo "PG Appears to be running!!"
    fi
}

startPG()
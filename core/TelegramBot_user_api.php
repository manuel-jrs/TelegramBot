<?php

# Copyright (c) 2018 Grigoriy Ermolaev (igflocal@gmail.com)
# TelegramBot for MantisBT is free software: 
# you can redistribute it and/or modify it under the terms of the GNU
# General Public License as published by the Free Software Foundation, 
# either version 2 of the License, or (at your option) any later version.
#
# TelegramBot plugin for for MantisBT is distributed in the hope 
# that it will be useful, but WITHOUT ANY WARRANTY; without even the 
# implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
# See the GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Customer management plugin for MantisBT.  
# If not, see <http://www.gnu.org/licenses/>.

function telegram_bot_user_mapping_add( $p_user_id, $p_telegram_user_id ) {

    $t_user_id          = (int) $p_user_id;
    $t_telegram_user_id = (int) $p_telegram_user_id;

    $t_user_relationship_table = plugin_table( 'user_relationship' );

    $query = "INSERT INTO $t_user_relationship_table
                                                ( mantis_user_id, telegram_user_id )
                                              VALUES
                                                ( " . db_param() . ',' . db_param() . ')';

    db_query( $query, array( $t_user_id, $t_telegram_user_id ) );

    return true;
}

function telegram_bot_user_mapping_delete( $p_user_id ) {

    $t_user_relationship_table = plugin_table( 'user_relationship' );

    $query = "DELETE FROM $t_user_relationship_table";

    $query .= " WHERE mantis_user_id=" . db_param();

    $t_fields[] = $p_user_id;

    db_query( $query, $t_fields );

    return true;
}

function user_get_id_by_telegram_user_id( $p_telegram_user_id ) {

    $t_user_relationship_table = plugin_table( 'user_relationship' );

    db_param_push();

    $t_query  = "SELECT mantis_user_id 
			FROM $t_user_relationship_table
			WHERE telegram_user_id=" . db_param();
    $t_result = db_query( $t_query, array( $p_telegram_user_id ) );

    $t_row     = db_fetch_array( $t_result );
    $t_user_id = $t_row['mantis_user_id'];

    return (int) $t_user_id;
}

function telegram_user_get_id_by_user_id( $p_mantis_user_id ) {

    $t_user_relationship_table = plugin_table( 'user_relationship' );

    db_param_push();

    $t_query  = "SELECT telegram_user_id 
			FROM $t_user_relationship_table
			WHERE mantis_user_id=" . db_param();
    $t_result = db_query( $t_query, array( $p_mantis_user_id ) );

    $t_row     = db_fetch_array( $t_result );
    $t_user_id = $t_row['telegram_user_id'];

    return (int) $t_user_id;
}

function telegram_user_is_associated_mantis_user( $p_telegram_user_id ) {

    $t_user_id = user_get_id_by_telegram_user_id( $p_telegram_user_id );

    if( $t_user_id == 0 ) {
        return false;
    } else {
        return true;
    }
}

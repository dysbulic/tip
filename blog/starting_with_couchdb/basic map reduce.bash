#!/bin/bash

HOST="localhost"
BASEURL="http://${HOST}:5984/"
CMD="curl --silent -X"
DEL="n"

DBNAME="fruits"
DB="${BASEURL}${DBNAME}"
ADD='${CMD} PUT ${DB}'
ADDE=$(eval echo $ADD)
echo "${ADDE}"
eval ${ADDE}
echo

NAME="apple"
CONTENT="{
    \\\"item\\\" : \\\"${NAME}\\\",
    \\\"prices\\\" : {
        \\\"Fresh Mart\\\" : 5.99,
        \\\"Price Max\\\" : 1.59,
        \\\"Apples Express\\\" : 0.79
    }
}"
TYPE="-H \"Content-Type: application/json\""
RCRD="${ADD}/${NAME} ${TYPE} -d \"${CONTENT}\""
echo "${RCRD}"
eval ${RCRD}
echo

NAME="banana"
CONTENT="{
    \\\"item\\\" : \\\"${NAME}\\\",
    \\\"prices\\\" : {
        \\\"Fresh Mart\\\" : 0.33,
        \\\"Price Max\\\" : 1.58,
        \\\"Apples Express\\\" : 0.28
    }
}"
RCRD="${ADD}/${NAME} ${TYPE} -d \"${CONTENT}\""
echo "${RCRD}"
eval ${RCRD}
echo

NAME="coconut"
CONTENT="{
    \\\"item\\\" : \\\"${NAME}\\\",
    \\\"prices\\\" : {
        \\\"Fresh Mart\\\" : 0.50,
        \\\"Price Max\\\" : 10.00,
        \\\"Apples Express\\\" : 28
    }
}"
RCRD="${ADD}/${NAME} ${TYPE} -d \"${CONTENT}\""
echo "${RCRD}"
eval ${RCRD}
echo

VIEW="function(doc) {
    var store, price, value;
    if( doc.item && doc.prices ) {
        for( store in doc.prices ) {
            price = doc.prices[store];
            value = [doc.item, store];
            emit( price, value );
        }
    }
}"
CONTENT="{ \\\"map\\\": \\\"${VIEW}\\\" }"
PGM="${CMD} POST ${DB}/_temp_view ${TYPE} -d \"${CONTENT}\""
echo "${PGM}"
eval ${PGM}
echo

VIEW="function(doc) {
    var store, price, value;
    if( doc.item && doc.prices ) {
        for( store in doc.prices ) {
            price = doc.prices[store];
            key = [doc.item, price];
            emit( price, store );
        }
    }
}"
CONTENT="{ \\\"map\\\": \\\"${VIEW}\\\" }"
PGM="${CMD} POST ${DB}/_temp_view ${TYPE} -d \"${CONTENT}\""
echo "${PGM}"
eval ${PGM}
echo

UUID="${CMD} GET ${BASEURL}_uuids?count=10"

OBJ="${DB}/apple"
LS="curl --head --silent ${OBJ}"
REVID="${LS} | sed -e '/^Etag: */!d; s/.*\"\(.*\)\"/\1/; s/.$//;q' | cat -v"
echo "${REVID}"
REVID=$(eval ${REVID})
echo "${REVID}"
echo

PROG=$(basename "$0")
META="${CMD} PUT ${OBJ}/program?rev=${REVID} --data-binary @\"${PROG}\" -H \"Content-Type: application/bash\""
echo "${META}"
eval ${META}
echo

LS="curl --head --silent ${OBJ}?attachments=true"

DB="${DB}-repl"
ADDE=$(eval echo $ADD)
echo "${ADDE}"
eval ${ADDE}
echo

REPL="${CMD} POST ${BASEURL}_replicate -d '{\"source\": \"${DBNAME}\", \"target\": \"${DBNAME}-repl\"}'"
echo "${REPL}"
eval ${REPL}
echo


[ -z "${DEL}" ] && (
    DEL="${CMD} DELETE ${DB}"
    echo "${DEL}"
    eval $DEL
)

#/bin/bash

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

"$DIR/../vendor/bin/phpunit" -c "$DIR/phpunit.xml" --colors --testsuite cotcot

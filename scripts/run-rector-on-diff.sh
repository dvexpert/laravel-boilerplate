#!/bin/bash

# TO RUN RECTOR ON STAGED FILES

# Record the start time
start_time=$(date +%s)

echo "Running Rector....\n"

# get all the staged files
# --cached options for staged changes only
files=$(git diff --cached --name-only --diff-filter=ACMR -- '*.php')

# Only if there php file changes then only run lint and test
if [ "x$files" != "x" ]; then
   vendor/bin/rector process $files
fi
# Record the end time
end_time=$(date +%s)

# Calculate the execution time
execution_time=$((end_time - start_time))

echo "Total execution time: $execution_time seconds"

exit 0 # indicating success

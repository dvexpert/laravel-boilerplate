#!/bin/bash

# Record the start time
start_time=$(date +%s)


echo "Linting ....\n"

# get all the staged files
# --cached options for staged changes only
files=$(git diff --cached --name-only --diff-filter=ACMR -- '*.php')

frontFiles=$(git diff --cached --name-only --diff-filter=ACMR -- '*.js' '*.ts' '*.vue')

# Only if there php file changes then only run lint and test
if [ "x$files" != "x" ]; then
    # Run php-cs-fixer via laravel/pint
    vendor/bin/pint $files
    pint_exit_code=$?

    # echo "Pint exit code: $pint_exit_code"  # Output the exit code
    # check if pint run successfully and has no errors.
    if [ "$pint_exit_code" == 1 ]; then
        echo "Pint failed with exit code $pint_exit_code. Please fix linting errors before committing."
        exit 1 # Indicate failure to Git
    fi

fi

echo "Lint Completed."

if [ "x$frontFiles" != "x" ]; then
    # Run eslint
    echo "Running eslint..."
    yarn eslint $frontFiles --fix
    eslint_exit_code=$?

    # check if eslint run successfully and has no errors.
    if [ "$eslint_exit_code" == 1 ]; then
        echo "Eslint failed with exit code $eslint_exit_code. Please fix linting errors before committing."
        exit 1 # Indicate failure to Git
    fi
fi

echo "Committing changes to git..."

# Record the end time
end_time=$(date +%s)

# Calculate the execution time
execution_time=$((end_time - start_time))

echo "Total execution time: $execution_time seconds"

exit 0 # indicating success to Git Commit

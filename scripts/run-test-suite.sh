#!/bin/bash

# Run `./run-test-suite.sh --init`
# or `./run-test-suite.sh --initOnly`

# to wipe and recreate the test db without running test cases

# Record the start time
start_time=$(date +%s)

echo "Running Test Suite ....\n"

if [ ! -f .env.testing ]; then
    echo ".env.testing file not found. Please create a new testing env file otherwise you main database data will be lost."
    exit 1
fi

# Default value for the initOnly flag
initOnly=false

# Parse command-line arguments
for arg in "$@"; do
    if [[ "$arg" == "--init" || "$arg" == "--initOnly" ]]; then
        initOnly=true
    fi
done


echo "Setting up fresh testing db using .env.testing configuration...."

php artisan db:wipe --env=testing
php artisan migrate --env=testing
echo "seeding database..."
php artisan db:seed -n -q --env=testing
rm -rf /home/teq001/projects/isi-abrod-api/storage/framework/testing/disks/*
php artisan optimize:clear --env=testing
php artisan cache:clear --env=testing

if [ "$initOnly" = true ]; then
    echo "DB initalized for testing...";
    exit 1
fi


# Run Pest tests
echo "Running Unit Test..."
php artisan test --env=testing --coverage-html coverage
pest_exit_code=$?

# check if tests run successfully and has no errors.
if [ "$pest_exit_code" == 1 ]; then
    echo "Feature tests failed with exit code $pest_exit_code. Please fix tests before committing."
    exit 1 # Indicate failure to Git
fi

# Record the end time
end_time=$(date +%s)

# Calculate the execution time
execution_time=$((end_time - start_time))

echo "Total execution time: $execution_time seconds"

exit 0 # indicating success

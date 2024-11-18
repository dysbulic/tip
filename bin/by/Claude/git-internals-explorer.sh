#!/bin/bash

# Create a new Git repository and directory structure
init_repo() {
    rm -rf test-repo
    mkdir test-repo
    cd test-repo
    git init

    # Create directory structure
    mkdir -p dir1/subdir1
    mkdir -p dir2
    
    # Create some files with content
    echo "Hello from file1" > dir1/file1.txt
    echo "Nested file content" > dir1/subdir1/file2.txt
    echo "Another file here" > dir2/file3.txt
    
    # Add and commit
    git add .
    git commit -m "Initial commit with directory structure"
}

# Function to display object details
show_object_details() {
    local object_hash=$1
    echo "Object: $object_hash"
    echo "Type: $(git cat-file -t $object_hash)"
    echo "Content:"
    git cat-file -p $object_hash
    echo "----------------------------------------"
}

# Main script
echo "Creating repository and directory structure..."
init_repo

echo -e "\nDirectory Structure Created:"
tree

echo -e "\nGit Object Database Contents:"
# Get the commit hash
commit_hash=$(git rev-parse HEAD)
echo "Commit Hash: $commit_hash"
show_object_details $commit_hash

# Get the tree hash from the commit
tree_hash=$(git rev-parse HEAD^{tree})
echo -e "\nMain Tree Hash: $tree_hash"
show_object_details $tree_hash

echo -e "\nAll Objects in Repository:"
# Find all objects and show their details
find .git/objects -type f -not -path "*/pack/*" | while read object_path; do
    # Extract hash from path
    dir=$(basename $(dirname $object_path))
    file=$(basename $object_path)
    hash="${dir}${file}"
    
    echo -e "\nExamining object: $hash"
    show_object_details $hash
done

echo -e "\nVisual Representation of Object Relationships:"
git cat-file --batch-check --batch-all-objects | \
while read hash type size; do
    case $type in
        "commit")
            echo "$hash [shape=box,label=\"Commit\"]"
            parent=$(git rev-parse $hash^{tree})
            echo "$hash -> $parent"
            ;;
        "tree")
            echo "$hash [shape=circle,label=\"Tree\"]"
            git ls-tree $hash | while read mode type object_hash file; do
                echo "$hash -> $object_hash"
            done
            ;;
        "blob")
            echo "$hash [shape=ellipse,label=\"Blob\"]"
            ;;
    esac
done

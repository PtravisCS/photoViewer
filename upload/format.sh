#!/bin/bash

# Get a list of duplicate files and delete them
file_list=$( find . -type f -exec ls '../img/{}' ';' 2> /dev/null )

if [ ${#file_list[@]} -gt 0 ]; then

  for i in $file_list; do

    [ -f "$i" ] || break
    rm -f "./$i"

  done

fi

# Get a list of pictures, copy the original to ../img and
# make a thumnail version and place it in ../thumbs
count=(`find ./ -maxdepth 1 -name "*.jpg"`)

if [ ${#count[@]} -gt 0 ]; then

  rename 's/img/IMG/' ./*.jpg
	cp ./*.jpg ../img/ 
	mogrify -resize 250x250 *.jpg 
	mv ./*.jpg ../thumbs/

fi

# Get a list of movies, copy the original to ../img and
# grab a frame from the start of the movie to use as a thumbnail
# place the thumbnail in ../thumbs
count=(`find ./ -maxdepth 1 -name "*.mp4"`)

if [ ${#count[@]} -gt 0 ]; then

  #for f in *.mp4; do 
  #  mv "$f" "$(echo "$f" | sed -E s/VID/IMG/I)" 
  #done

  rename 's/VID/IMG/' ./*.mp4

	for i in *.mp4; do
	    [ -f "$i" ] || break
	    j=${i%.*}
	    ffmpeg -i $i -ss 00:00:01.000 -vframes 1 "$j.png" > /dev/null 2> /dev/null
	done

	mogrify -resize 250x250 *.png
	mv ./*.png ../thumbs/
	mv ./*.mp4 ../img/

fi

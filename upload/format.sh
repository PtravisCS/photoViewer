#!/bin/bash

# Get a list of duplicate files and delete them
file_list=$( find . -type f -exec ls '../img/{}' ';' 2> /dev/null )

if [ ${#file_list[@]} -gt 0 ]; then

  for i in $file_list; do

    [ -f "$i" ] || break
    rm -f "./$i"

  done

fi

# Get a list of pictures, copy the original to ../img
formats=( 'jpg' 'png' 'gif' 'apng' 'avif' 'jpeg' 'svg' 'webp' 'bmp' 'tiff' )

for i in "${formats[@]}"; do
  count=(`find ./ -maxdepth 1 -name "*.$i"`)

  if [ ${#count[@]} -gt 0 ]; then
    rename 's/img/IMG/' "./*.$i"

    for j in *."$i"; do
      [ -f "$j" ] || break
      
      exiftool -if 'not defined $DateTimeOriginal' -DateTimeOriginal=now -overwrite_original -- ./"$j" &> /dev/null
      #mv "$j" "$(date +%Y%m%dT%H%M%S%3N%z).$i"
    done

    mv ./*."$i" ../img/ 
  fi
done

# Get a list of movies, copy the original to ../img and
# grab a frame from the start of the movie to use as a thumbnail
# place the thumbnail in ../thumbs
count=(`find ./ -maxdepth 1 -name "*.mp4"`)

if [ ${#count[@]} -gt 0 ]; then

  #for f in *.mp4; do 
  #  mv "$f" "$(echo "$f" | sed -E s/VID/IMG/I)" 
  #done

  rename 's/VID/IMG/' ./*.mp4

	#for i in *.mp4; do
	#    [ -f "$i" ] || break
	#    j=${i%.*}
	#    ffmpeg -i $i -ss 00:00:01.000 -vframes 1 "$j.png" > /dev/null 2> /dev/null
	#done

	#mogrify -resize 250x250 *.png
	#mv ./*.png ../thumbs/

  #strip audio track from video
  for i in *.mp4; do
    [ -f "$i" ] || break
    ffmpeg -hide_banner -loglevel error -nostats -i "$i" -c copy -an ./temp.mp4
    mv ./temp.mp4 "./$i"
  done

	mv ./*.mp4 ../img/

fi

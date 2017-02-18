# AnnoTool
## Description
AnnoTool is a simple php based tool for annotating images with labels. 
Intended use case is generating training data for machine learning. AnnoTool has been successfully 
used in a clinical study for machine learning on medical time series.

## Usage
Configuration is done within index.html

Images are defined in csv files (location is configurable in index.html)

All images have to be in the same folder and the filename must be specified in the csv (without file ending)

Results are written back in the csv (0 means not annotated)

Each csv is addressable via the corresponding filename within the url (in this example: http://localhost/#abcd)

## License
MIT 

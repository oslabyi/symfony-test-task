## Decathlon leaderboard task

#### Installation
 - Download source code from [this link](http://vikimedija.lt/developers-task/developers-task.zip)
 - Extract archive to desired location
 - Copy `.env` file from `.env.dist`
 - Run `composer install` to install all dependencies
 - Run `php -S 127.0.0.1:8000 -t public` to launch project server *(optional)*
 - **Feel free to modify any existing code**
 
 ---
 
#### Objectives
- Modify existing form to accept decathlon results in CSV file
- Evaluate each athletes place in leaderboard using decathlon points system  (https://en.wikipedia.org/wiki/Decathlon#Points_system)
  - If two or more athletes have same score their positions should be written as following example (default order in this case by athlete name):
    - | Position | Athlete         | Total score | ... |
      | -------- | --------------- | ----------- | --- |
      | 1        |  Coos Kwesi     | 30          ||
      | 2-3      |  Edan Daniele   | 15          ||
      | 2-3      |  Lehi Poghos    | 15          ||
      | 4        | Severi EileifrA | 5           ||
      
- Display leaderboard, containing:
  - Athlete position
  - Athlete name
  - Total score
  - Scores of each event
- Ability to sort leaderboard by each column  
- Ability to export leaderboard to XML file of following format:
```xml
<Athletes>
  <Athlete>
    <Name>...</Name>
    <Total>...</Total>
    <Place>...</Place>
    <Results>
        <Result>
            <Event>...</Event>
            <Performance>...</Performance>
            <Score>...</Score>
        </Result>
        ...
    </Results>
  </Athlete>
  ...
</Athletes>
```

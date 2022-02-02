# Monsoon Tech Exam - Weather
This module provides weather block from https://openweathermap.org/.

## Installation
1. Add a composer repository `otarza/monsoon_tech_exam`:
```
{
  "type": "package",
  "package": {
      "name": "otarza/monsoon_tech_exam",
      "version": "2.0",
      "type":"drupal-module",
      "source": {
          "url": "https://github.com/otarza/monsoon_tech_exam.git",
          "type": "git",
          "reference": "master"
      }
  }
}
```
2. Require composer package: `composer require otarza/monsoon_tech_exam`;
3. Enable module: `drush en monsoon_tech_exam`;
4. Configure module from: (Configuration > Web services > Open Weather API) `/admin/config/services/weather`
5. Add Weather Block in your regions.

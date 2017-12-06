Feature: Fixtures

Scenario: fixture 1
  Given account:
    | username       |  password      |  email                     |   roles                 |
    | staff          |  staff         |  staff@shinigami.com       |   ROLE_STAFF            |
    | superstaff     |  staff         |  superstaff@shinigami.com  |   ROLE_SUPER_ADMIN      |
  Given center:
    | code         | adress                                       |
    | 123          | 157, boulevard MacDonald 75019 Paris         |
    | 985         | 36, rue du petit chemin                          |
    | code         | adress                         |

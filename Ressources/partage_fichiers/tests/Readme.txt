http://people.brunel.ac.uk/~mastjjb/jeb/orlib/schinfo.html



The data files are:
  sch10, sch20, sch50, sch100, sch200, sch500, sch1000

The format of these data files is:
    number of problems
    for each problem in turn:
       number of jobs (n)
       for each job i (i=1,...,n) in turn:
          p(i), a(i), b(i)

The common due date d is calculated by:

d = round [SUM_P * h] 

where round[X] gives the biggest integer which is smaller then or equal to X;

Sum_P denotes the sum of the processing times of the n jobs and
the parameter h is used to calculate more or less restrictive common due dates.
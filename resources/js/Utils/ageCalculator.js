import { differenceInYears, parseISO } from 'date-fns';

export function calculateAge(birthDate, deathDate = null) {
  if (!birthDate) return null;
  
  const endDate = deathDate ? parseISO(deathDate) : new Date();
  const birth = parseISO(birthDate);
  
  return differenceInYears(endDate, birth);
}
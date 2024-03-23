import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'courseAvailableFilter',
  standalone: true
})
export class CourseAvailableFilterPipe implements PipeTransform {

  transform(value: any[], search: string = ''): any[] {
    if (!search) {
      return value;
    }

    search = search.toLowerCase();
    return value.filter(item => {
      const courseCodeLower = (item as any).subjects.subjectcode?.toLowerCase() || '';
      const subjectDescLower = (item as any).subjects.subjectname?.toLowerCase() || '';
      const combinedString = courseCodeLower + ' ' + subjectDescLower;

      return combinedString.includes(search);
    });
  }

}

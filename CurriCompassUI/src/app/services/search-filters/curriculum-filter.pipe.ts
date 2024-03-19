import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'curriculumFilter',
  standalone: true
})
export class CurriculumFilterPipe implements PipeTransform {

  transform(value: any[], search: string = ''): any[] {
    if (!search) {
      return value;
    }

    search = search.toLowerCase();
    return value.filter(item => {
      const programCode = (item as any).program.programcode?.toLowerCase() || '';
      const  programDesc = (item as any).program.programdesc?.toLowerCase() || '';
      const specialization = (item as any).specialization?.toLowerCase() || '';

      const combinedString = programCode + ' ' + programDesc + ' ' + specialization + ' ' + programCode;
      return combinedString.includes(search);
    });
  }
}

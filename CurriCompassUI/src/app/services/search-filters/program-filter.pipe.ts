import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'programFilter',
  standalone: true
})
export class ProgramFilterPipe implements PipeTransform {

  transform(value: any[], search: string = ''): any[] {
    if (!search) {
      return value;
    }

    search = search.toLowerCase();
    return value.filter(item => {
      const programCode = (item as any).programcode?.toLowerCase() || '';
      const  programDesc = (item as any).programdesc?.toLowerCase() || '';

      const combinedString = programCode + ' ' + programDesc;
      return combinedString.includes(search);
    });
  }
}

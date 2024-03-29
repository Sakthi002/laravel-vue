import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

export function useToastr () {

    toastr.options = {
        positionClass : 'toast-bottom-right',
        closeButton: true,
        progressBar: true
    };

    return toastr;
}

const ff = {};

ff.money = (val) => {
    try {
        if (!val) return 0;

        return new Intl.NumberFormat("th-TH", {
            style: "currency",
            currency: "THB",
        }).format(val);
    } catch (error) {
        return 0;
    }
};

ff.number = (val) => {
    try {
        if (!val) return 0;

        return val.toLocaleString();
    } catch (error) {
        return 0;
    }
};

ff.dateandtime = (val) => {
    try {
        return new Intl.DateTimeFormat("th-TH", {
            timeZone: "Asia/Bangkok",
            year: "numeric",
            month: "long",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        }).format(new Date(val));
    } catch (error) {
        return "-";
    }
};

ff.date = (val) => {
    try {
        return new Intl.DateTimeFormat("th-TH", {
            timeZone: "Asia/Bangkok",
            year: "numeric",
            month: "long",
            day: "numeric",
        }).format(new Date(val));
    } catch (error) {
        return "-";
    }
};

ff.itemsvalue = (items) => {
    try {
        return ff.money(
            items.reduce((total, item) => total + item.amount * item.value, 0)
        );
    } catch (error) {
        return ff.money(0);
    }
};

ff.invoice = ({ start, end, status }) => {
    if (status == -1) return -1;
    if (status == 1) return 1;
    if (status == 2) return 2;
    if (status == 0) {
        if (dayjs().isBetween(dayjs(start), dayjs(end))) return 3;
        if (dayjs().isAfter(dayjs(end))) return 4;
    }

    return 0;
};

ff.invoice_label = (status, colored = false) => {
    const getLabel = () => {
        switch (status) {
            case -1:
                return "ยกเลิกแล้ว";
            case 0:
                return "รอกำหนดการ";
            case 1:
                return "ชำระแล้ว";
            case 2:
                return "กำลังรอตรวจสอบ";
            case 3:
                return "กำลังดำเนินการ";
            case 4:
                return "เลยกำหนด";
            default:
                return "กำลังดำเนินการ";
        }
    };

    const getColor = () => {
        switch (status) {
            case -1:
                return "secondary";
            case 0:
                return "info";
            case 1:
                return "success";
            case 2:
                return "primary";
            case 3:
                return "warning";
            case 4:
                return "danger";
            default:
                return "warning";
        }
    }

    if (!colored) return getLabel();
    return `<span class="text-${getColor()}">${getLabel()}</span>`
};
